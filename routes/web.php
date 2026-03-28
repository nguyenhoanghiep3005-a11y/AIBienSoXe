<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;
// Khai báo thêm các Controller khác nếu bạn đã tạo
// use App\Http\Controllers\EmployeeController;
// use App\Http\Controllers\PriceConfigController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi đăng ký toàn bộ các đường dẫn (URL) cho ứng dụng Web của bạn.
|
*/

// 1. TRANG CHỦ (DASHBOARD)
// Trỏ vào file resources/views/admin/dashboard.blade.php
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


// 2. QUẢN LÝ NHÂN VIÊN / TÀI KHOẢN
// Gom nhóm các router liên quan đến nhân viên
Route::prefix('employee')->name('employee.')->group(function () {
    // Trang danh sách nhân viên (Ví dụ url: /employee)
    Route::get('/', function () {
        return view('admin.employee.index'); // Tạm thời trả về view
        // Chỉnh lại thành Controller khi có: [EmployeeController::class, 'index']
    })->name('index');
    
    // Thêm các route thêm/sửa/xóa ở đây...
});


// 3. QUẢN LÝ PHƯƠNG TIỆN & CẤU HÌNH GIÁ
// Gom nhóm các router liên quan đến giá xe
Route::prefix('price')->name('price.')->group(function () {
    // Trang danh sách cấu hình giá (Ví dụ url: /price)
    Route::get('/', function () {
        return view('admin.price.index'); // Tạm thời trả về view
        // Chỉnh lại thành Controller khi có: [PriceConfigController::class, 'index']
    })->name('index');

    // Thêm các route thêm/sửa/xóa ở đây...
});


// 4. HỆ THỐNG NHẬN DIỆN BIỂN SỐ (Gọi vào ParkingController trên Canvas)
Route::prefix('parking')->name('parking.')->group(function () {
    // Hiển thị giao diện camera và lịch sử xe (GET)
    Route::get('/', [ParkingController::class, 'index'])->name('index');

    // Các API (POST) được Javascript ở Frontend gọi tới để xử lý ảnh
    Route::post('/check-in', [ParkingController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out', [ParkingController::class, 'checkOut'])->name('checkout');
    Route::post('/recognize', [ParkingController::class, 'recognize'])->name('recognize');
});