@extends('layouts.admin')

@section('content')
<div class="text-center mb-5">
    <h2 class="text-secondary font-weight-bold">CHÀO MỪNG BẠN ĐẾN VỚI HỆ THỐNG</h2>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-box bg-teal p-4 shadow">
            <i class="fas fa-users fa-3x mb-2"></i>
            <h5>QUẢN LÝ TÀI KHOẢN / NHÂN VIÊN</h5>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card-box bg-yellow p-4 shadow">
            <i class="fas fa-car fa-3x mb-2"></i>
            <h5>PHƯƠNG TIỆN GIAO THÔNG</h5>
        </div>
    </div>
    <div class="col-md-6 mb-4" onclick="window.location='/parking'">
        <div class="card-box bg-green p-4 shadow">
            <i class="fas fa-microchip fa-3x mb-2"></i>
            <h5>NHẬN DIỆN BIỂN SỐ</h5>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card-box bg-purple p-4 shadow">
            <i class="fas fa-user-check fa-3x mb-2"></i>
            <h5>NHẬN DIỆN KHUÔN MẶT</h5>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card-box bg-orange p-4 shadow text-center">
            <i class="fas fa-camera fa-3x mb-2"></i>
            <h5>NHẬN DIỆN HÌNH ẢNH BIỂN SỐ</h5>
        </div>
    </div>
</div>
@endsection