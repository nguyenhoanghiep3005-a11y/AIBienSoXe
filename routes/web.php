<?php
use App\Http\Controllers\ParkingController;

Route::get('/', [ParkingController::class, 'index'])->name('parking.index');
Route::post('/recognize', [ParkingController::class, 'recognize'])->name('parking.recognize');