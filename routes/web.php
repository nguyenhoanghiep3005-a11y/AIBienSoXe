<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParkingController;

// Trang chủ Dashboard
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Trang hiển thị danh sách nhận diện
Route::get('/parking', [ParkingController::class, 'index'])->name('parking.index');

// Route xử lý nhận diện (Dòng này đang thiếu cái ->name(...) nên bị lỗi)
Route::post('/parking/recognize', [ParkingController::class, 'recognize'])->name('parking.recognize');

// Các route phụ để khớp giao diện
Route::get('/employee', function () { return view('employee.index'); })->name('employee.index');
Route::get('/price', function () {
    return view('price.index');
})->name('price.index');
// Authentication Routes
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/forgot-password', function () { return view('auth.forgot'); })->name('password.request');