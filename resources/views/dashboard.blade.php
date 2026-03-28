@extends('admin')

@section('title', 'Trang chủ Hệ thống')

@section('content')
<div class="text-center mb-5 mt-4">
    <h1 class="text-secondary fw-bold" style="letter-spacing: 1px;">CHÀO MỪNG BẠN ĐẾN VỚI HỆ THỐNG</h1>
</div>

<div class="row justify-content-center g-4">
    <div class="col-md-4">
        <div class="card shadow border-0 p-4 text-center h-100 cursor-pointer hover-effect" 
             onclick="window.location='{{ route('employee.index') }}'" style="cursor: pointer; transition: transform 0.3s;">
            <i class="fas fa-users fa-4x text-primary mb-3"></i>
            <h5 class="fw-bold text-uppercase mt-2">Quản lý tài khoản / Nhân viên</h5>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow border-0 p-4 text-center h-100 cursor-pointer hover-effect" 
             onclick="window.location='{{ route('price.index') }}'" style="cursor: pointer; transition: transform 0.3s;">
            <i class="fas fa-car fa-4x text-success mb-3"></i>
            <h5 class="fw-bold text-uppercase mt-2">Phương tiện giao thông</h5>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow border-0 p-4 text-center h-100 cursor-pointer hover-effect" 
             onclick="window.location='{{ route('parking.index') }}'" style="cursor: pointer; transition: transform 0.3s;">
            <i class="fas fa-microchip fa-4x text-warning mb-3"></i>
            <h5 class="fw-bold text-uppercase mt-2">Nhận diện biển số</h5>
        </div>
    </div>
</div>

<style>
    .hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>
@endsection