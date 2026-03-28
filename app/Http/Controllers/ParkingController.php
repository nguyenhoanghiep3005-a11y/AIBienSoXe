<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\ParkingLog;
use Carbon\Carbon;

class ParkingController extends Controller
{
    /**
     * Hiển thị danh sách lịch sử xe ra vào
     */
    public function index(Request $request)
    {
        // Khởi tạo query và load relationship (nếu model có định nghĩa belongsTo VehicleType)
        $query = ParkingLog::with('vehicleType');

        // Tính năng tìm kiếm theo biển số
        if ($request->has('plate') && $request->plate != '') {
            $query->where('plate_number', 'like', '%' . $request->plate . '%');
        }

        // Lấy danh sách, sắp xếp mới nhất lên đầu, phân trang 15 dòng/trang
        $logs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('parking.index', compact('logs'));
    }

    /**
     * Xử lý cho xe vào (Check-in)
     */
    public function checkIn(Request $request)
    {
        // 1. Kiểm tra ảnh
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $image = $request->file('image');

            // 2. Lưu ảnh vào storage public (Nhớ chạy: php artisan storage:link)
            $imagePath = $image->store('parking_images/checkin', 'public');

            // 3. Gửi ảnh sang Server AI (Port 8001)
            $response = Http::timeout(10)->attach(
                'image', 
                file_get_contents($image->getRealPath()), 
                $image->getClientOriginalName()
            )->post('http://localhost:8001/api/v1/parking/check-in');

            // 4. Xử lý kết quả AI
            if ($response->successful()) {
                $aiResult = $response->json();
                $plate = $aiResult['plate'] ?? null;

                if (!$plate) {
                    return response()->json(['success' => false, 'message' => 'AI không tìm thấy biển số trong ảnh.'], 400);
                }

                // 5. Kiểm tra logic: Xe đã ở trong bãi chưa?
                $isAlreadyIn = ParkingLog::where('plate_number', $plate)->where('status', 'in')->exists();
                if ($isAlreadyIn) {
                    return response()->json(['success' => false, 'message' => "Xe biển số $plate ĐANG Ở TRONG BÃI."], 400);
                }

                // 6. Lưu Database
                $log = ParkingLog::create([
                    'plate_number' => $plate,
                    'time_in' => Carbon::now(),
                    'status' => 'in',
                    // 'image_in' => $imagePath, // Bỏ comment nếu DB của bạn có cột này
                ]);

                $aiResult['time_in'] = Carbon::parse($log->time_in)->format('d/m/Y H:i');

                return response()->json([
                    'success' => true,
                    'message' => 'Xe vào thành công',
                    'data' => $aiResult
                ]);
            }

            Log::error('AI Check-in Error: ' . $response->body());
            return response()->json(['success' => false, 'message' => 'Hệ thống AI không nhận diện được'], 400);

        } catch (\Exception $e) {
            Log::error('Check-in Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Mất kết nối với Server AI.'], 500);
        }
    }

    /**
     * Xử lý cho xe ra (Check-out)
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120'
        ]);

        try {
            $image = $request->file('image');
            $imagePath = $image->store('parking_images/checkout', 'public');

            $response = Http::timeout(10)->attach(
                'image', 
                file_get_contents($image->getRealPath()), 
                $image->getClientOriginalName()
            )->post('http://localhost:8001/api/v1/parking/check-out');

            if ($response->successful()) {
                $aiResult = $response->json();
                $plate = $aiResult['plate'] ?? null;

                if (!$plate) {
                    return response()->json(['success' => false, 'message' => 'AI không tìm thấy biển số trong ảnh.'], 400);
                }

                $log = ParkingLog::where('plate_number', $plate)->where('status', 'in')->first();

                if (!$log) {
                    return response()->json(['success' => false, 'message' => "Không tìm thấy xe $plate trong bãi!"], 404);
                }

                $fee = $aiResult['fee'] ?? $this->calculateFee($log->time_in, Carbon::now());

                $log->update([
                    'time_out' => Carbon::now(),
                    'status' => 'out',
                    'fee' => $fee,
                    // 'image_out' => $imagePath // Bỏ comment nếu DB của bạn có cột này
                ]);

                $aiResult['fee'] = $fee;
                $aiResult['time_in'] = Carbon::parse($log->time_in)->format('d/m/Y H:i');

                return response()->json([
                    'success' => true,
                    'message' => 'Xe ra thành công',
                    'data' => $aiResult
                ]);
            }

            Log::error('AI Check-out Error: ' . $response->body());
            return response()->json(['success' => false, 'message' => 'Hệ thống AI từ chối'], 400);

        } catch (\Exception $e) {
            Log::error('Check-out Exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi máy chủ.'], 500);
        }
    }

    private function calculateFee($timeIn, $timeOut)
    {
        $timeIn = Carbon::parse($timeIn);
        $timeOut = Carbon::parse($timeOut);
        $hours = $timeIn->diffInHours($timeOut) + 1; 
        return $hours * 5000; // Mặc định 5k/giờ
    }
}