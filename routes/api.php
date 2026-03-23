<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('lpr')->group(function () {
    Route::post('/detect', [LprApiController::class, 'detect']);
    Route::post('/save', [LprApiController::class, 'save']);
    Route::get('/list', [LprApiController::class, 'list']);
});