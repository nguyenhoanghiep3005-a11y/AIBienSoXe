<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParkingController extends Controller
{
    public function index() {
        return view('parking.index');
    }

public function recognize(Request $request) {
    if (!$request->hasFile('plate_image')) {
        return response()->json(['error' => 'Chưa chọn ảnh'], 400);
    }

    try {
        $image = $request->file('plate_image');
        
        // Gửi ảnh sang Docker API
        $response = Http::attach(
            'file', file_get_contents($image), $image->getClientOriginalName()
        )->post('http://localhost:8001/api/v1/parking/check-in');

        if ($response->successful()) {
            $result = $response->json();
            
            // CẤU TRÚC MỚI ĐỂ LẤY BIỂN SỐ TỪ SWAGGER CỦA BẠN
            // Dữ liệu nằm ở: $result['detections'][0]['text']
            $plate = 'N/A';
            
            if (isset($result['detections']) && count($result['detections']) > 0) {
                $plate = $result['detections'][0]['text'];
            }

            return response()->json([
                'plate' => $plate
            ]);
        }
        
        return response()->json(['error' => 'API Docker không phản hồi'], 500);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Lỗi kết nối: ' . $e->getMessage()], 500);
    }
}
}