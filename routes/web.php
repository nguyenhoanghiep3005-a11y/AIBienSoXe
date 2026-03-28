<?php

use App\Http\Controllers\ParkingController;
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard')->name('dashboard');

Route::get('/price', [PriceController::class, 'index'])->name('price.index');
Route::post('/price', [PriceController::class, 'store'])->name('price.store');
Route::put('/price/{priceConfig}', [PriceController::class, 'update'])->name('price.update');
Route::delete('/price/{priceConfig}', [PriceController::class, 'destroy'])->name('price.destroy');

Route::prefix('parking')->name('parking.')->group(function () {
    Route::get('/', [ParkingController::class, 'index'])->name('index');
    Route::post('/recognize', [ParkingController::class, 'recognize'])->name('recognize');
    Route::post('/check-in', [ParkingController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out', [ParkingController::class, 'checkOut'])->name('checkout');
});
