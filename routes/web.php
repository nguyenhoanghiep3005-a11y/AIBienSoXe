<?php

use Illuminate\Support\Facades\Route;
Route::middleware(['auth'])->group(function () {
    Route::get('/lpr', [LprController::class, 'index'])->name('lpr.index');
});