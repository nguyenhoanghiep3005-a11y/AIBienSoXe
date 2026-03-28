@extends('layouts.admin')

@section('title', 'Trang chủ Hệ thống')

@section('content')
<div class="text-center mb-5 mt-4">
    <h1 class="text-secondary font-weight-bold" style="letter-spacing: 2px;">CHÀO MỪNG BẠN ĐẾN VỚI HỆ THỐNG</h1>
</div>

<div class="row justify-content-center">
    <!-- Thẻ: Quản lý Nhân viên -->
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0 p-4 text-center bg-white hover-card cursor-pointer" 
             onclick="window.location='{{ route('employee.index') ?? '#' }}'">
            <i class="fas fa-users fa-4x text-dark mb-3"></i>
            <h5 class="font-weight-bold text-uppercase">Quản lý tài khoản / Nhân viên</h5>
        </div>
    </div>

    <!-- Thẻ: Phương tiện giao thông & Giá -->
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0 p-4 text-center bg-white hover-card cursor-pointer" 
             onclick="window.location='{{ route('price.index') ?? '#' }}'">
            <i class="fas fa-car fa-4x text-dark mb-3"></i>
            <h5 class="font-weight-bold text-uppercase">Phương tiện giao thông</h5>
        </div>
    </div>

    <!-- Thẻ: Nhận diện biển số -->
    <div class="col-md-5 mb-4 text-center">
        <div class="card shadow-sm border-0 p-4 bg-white hover-card cursor-pointer" 
             onclick="window.location='{{ route('parking.index') ?? '#' }}'">
            <i class="fas fa-microchip fa-4x text-dark mb-3"></i>
            <h5 class="font-weight-bold text-uppercase">Nhận diện biển số</h5>
        </div>
    </div>
</div>
@endsection