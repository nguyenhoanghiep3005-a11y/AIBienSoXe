<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// QUAN TRỌNG: Khai báo các Controller để Laravel nhận diện được class
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. TRANG CHỦ & DASHBOARD
Route::get('/', function () { 
    return view('dashboard');
})->name('dashboard');


// 2. NHÓM NHẬN DIỆN BIỂN SỐ (PARKING)
Route::prefix('parking')->group(function () {
    // Trang chính nhận diện
    Route::get('/', [ParkingController::class, 'index'])->name('parking.index');
    
    // Xử lý API nhận diện (Gửi ảnh sang Docker)
    Route::post('/recognize', [ParkingController::class, 'recognize'])->name('parking.recognize');
});


// 3. NHÓM QUẢN LÝ NHÂN VIÊN (EMPLOYEE)
Route::prefix('employee')->group(function () {
    // Hiển thị danh sách và form thêm
    Route::get('/', [EmployeeController::class, 'index'])->name('employee.index');
    
    // Xử lý lưu nhân viên mới vào Database
    Route::post('/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::post('/account/store', [EmployeeController::class, 'storeAccount'])->name('account.store');
});


// 4. CÁC TRANG KHÁC (CẤU HÌNH GIÁ, AUTH)
Route::get('/price', function () {
    return view('price.index');
})->name('price.index');

// Nhóm Routes cho Authentication
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/forgot-password', function () { return view('auth.forgot'); })->name('password.request');


// 5. TOOL DỌN DẸP HỆ THỐNG (Dùng khi sửa code mà trình duyệt không nhận)
Route::get('/clear', function() {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return "Hệ thống đã được làm mới (Clear Cache) thành công!";
});