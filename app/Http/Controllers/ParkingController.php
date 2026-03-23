<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Dùng để gọi API AI
use App\Models\ParkingLog; // Model lưu lịch sử xe
use Carbon\Carbon;

class ParkingController extends Controller
{
    // 1. Hiển thị trang danh sách biển số
    public function index()
    {
        // Lấy danh sách xe mới nhất hiện lên đầu
        $logs = ParkingLog::orderBy('created_at', 'desc')->get();
        return view('parking.index', compact('logs'));
    }

    // 2. Xử lý nhận diện khi bấm nút "GỬI SANG AI"
    public function recognize(Request $request)
    {
        // Kiểm tra xem đã chọn ảnh chưa
        if (!$request->hasFile('plate_image')) {
            return back()->with('error', 'Vui lòng chọn một tấm ảnh!');
        }

        $file = $request->file('plate_image');

        try {
            // GỌI API AI (FastAPI đang chạy ở port 8079)
            // Lưu ý: Đảm bảo phần mềm AI của bạn đang chạy ở link này
            $response = Http::attach(
                'file', 
                file_get_contents($file), 
                $file->getClientOriginalName()
            )->post('http://localhost:8079/api/v1/recognize');

            if ($response->successful()) {
                $data = $response->json();
                $plate = $data['plate'] ?? 'Không nhận dạng được';

                // LƯU VÀO DATABASE
                ParkingLog::create([
                    'license_plate' => $plate,
                    'vehicle_type'  => 'Xe máy', // Mặc định hoặc bạn có thể bắt từ Form
                    'time_in'       => Carbon::now(),
                    'status'        => 'Đang gửi xe'
                ]);

                return back()->with('plate', $plate);
            }
            
            return back()->with('plate', 'Lỗi kết nối API AI!');

        } catch (\Exception $e) {
            return back()->with('plate', 'Lỗi: ' . $e->getMessage());
        }
    }
}