<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use App\Models\ParkingSession; // Mở comment này khi bạn đã có Model

class ParkingController extends Controller
{
    /**
     * 1. Hiển thị trang giao diện, kèm theo Lịch sử & Tìm kiếm
     */
    public function index(Request $request)
    {
        // Khởi tạo query từ bảng ParkingSession
        // $query = ParkingSession::query();

        // Nếu có nhập khóa tìm kiếm (biển số)
        // if ($request->has('plate') && $request->plate != '') {
        //     $query->where('plate_number', 'like', '%' . $request->plate . '%');
        // }

        // Lấy danh sách, sắp xếp mới nhất, phân trang 10 dòng/trang
        // $sessions = $query->orderBy('created_at', 'desc')->paginate(10);

        // Tạm thời truyền mảng rỗng nếu chưa có Model/Database
        $sessions = [];

        return view('admin.parking.index', compact('sessions'));
    }

    /**
     * 2. Nhận ảnh từ giao diện, gọi API AI Check-in
     */
    public function checkIn(Request $request)
    {
        // Validate đảm bảo file upload lên là ảnh hợp lệ
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // max 5MB
        ]);

        try {
            $image = $request->file('image');

            // Dùng HTTP Client của Laravel để gọi sang Server AI (Port 8001)
            $response = Http::attach(
                'image', 
                file_get_contents($image->getRealPath()), 
                $image->getClientOriginalName()
            )->post('http://localhost:8001/api/v1/parking/check-in');

            // Xử lý kết quả trả về từ Server AI
            if ($response->successful()) {
                $aiResult = $response->json();

                // TẠI ĐÂY: Bạn có thể code thêm logic lưu Database của Laravel
                // ParkingSession::create([
                //     'plate_number' => $aiResult['plate'],
                //     'time_in' => now(),
                //     'status' => 'Đang đỗ'
                // ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Xe vào thành công',
                    'data' => $aiResult
                ]);
            }

            return response()->json([
                'success' => false, 
                'message' => 'AI Server không thể nhận diện được',
                'error' => $response->json()
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Lỗi kết nối tới Server Nhận diện (AI đang tắt?)',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 3. Nhận ảnh từ giao diện, gọi API AI Check-out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $image = $request->file('image');

            $response = Http::attach(
                'image', 
                file_get_contents($image->getRealPath()), 
                $image->getClientOriginalName()
            )->post('http://localhost:8001/api/v1/parking/check-out');

            if ($response->successful()) {
                $aiResult = $response->json();

                // TẠI ĐÂY: Logic cập nhật giờ ra và tính tiền vào Database
                // $session = ParkingSession::where('plate_number', $aiResult['plate'])->whereNull('time_out')->first();
                // $session->update(['time_out' => now(), 'status' => 'Đã ra', 'fee' => $aiResult['fee']]);

                return response()->json([
                    'success' => true,
                    'message' => 'Xe ra thành công',
                    'data' => $aiResult
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Lỗi từ AI Server'], 400);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi máy chủ: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 4. Hàm test nhận diện lẻ (chỉ đọc biển số)
     */
    public function recognize(Request $request)
    {
        $request->validate(['image' => 'required|image']);

        try {
            $image = $request->file('image');
            $response = Http::attach(
                'image', file_get_contents($image->getRealPath()), $image->getClientOriginalName()
            )->post('http://localhost:8001/api/v1/recognize');

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}