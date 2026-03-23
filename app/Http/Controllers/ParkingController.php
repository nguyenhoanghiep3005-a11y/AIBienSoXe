<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ParkingLog;
// Không cần dùng "use App\Http\Controllers\Controller" vì nó cùng thư mục

class ParkingController extends Controller
{
    public function index() {
        $logs = ParkingLog::orderBy('created_at', 'desc')->get();
        return view('parking.index', compact('logs'));
    }

    public function recognize(Request $request) {
        $file = $request->file('plate_image');
        
        // Gửi sang API AI của bạn
        $response = Http::attach(
            'file', file_get_contents($file), $file->getClientOriginalName()
        )->post('http://localhost:8079/api/v1/recognize');

        $plate = $response->json()['plate'] ?? 'N/A';

        // Lưu vào DB
        ParkingLog::create([
            'license_plate' => $plate,
            'vehicle_type' => 'Xe máy',
            'time_in' => now(),
            'status' => 'Đang gửi xe'
        ]);

        return back()->with('plate', $plate);
    }
}