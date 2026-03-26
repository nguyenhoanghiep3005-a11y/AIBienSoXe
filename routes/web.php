<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

// Route xử lý Check-in
Route::post('/api/proxy/check-in', function (Request $request) {
    $request->validate(['image' => 'required|image']);

    $image = $request->file('image');
    
    // Gửi request sang FastAPI (Đổi port 8001 theo port thực tế của FastAPI)
    $response = Http::attach(
        'file', file_get_contents($image), $image->getClientOriginalName()
    )->post('http://127.0.0.1:8001/api/v1/parking/check-in'); 

    return $response->json();
});

// Route xử lý Check-out
Route::post('/api/proxy/check-out', function (Request $request) {
    $request->validate(['image' => 'required|image']);

    $image = $request->file('image');
    
    // Gửi request sang FastAPI
    $response = Http::attach(
        'file', file_get_contents($image), $image->getClientOriginalName()
    )->post('http://127.0.0.1:8001/api/v1/parking/check-out');

    return $response->json();
});
