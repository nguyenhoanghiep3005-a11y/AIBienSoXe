@extends('layouts.auth')

@section('content')
<div class="card glass-card border-0">
    <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
            <h3 class="font-weight-bold text-dark">ĐĂNG KÝ</h3>
            <p class="text-muted small">Tạo tài khoản quản lý bãi xe</p>
        </div>
        
        <form>
            <div class="form-group mb-3">
                <label class="small font-weight-bold">EMAIL</label>
                <input type="email" class="form-control" placeholder="example@gmail.com" required>
            </div>
            
            <div class="form-group mb-3">
                <label class="small font-weight-bold">MẬT KHẨU</label>
                <input type="password" class="form-control" placeholder="Ít nhất 8 ký tự" required>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold">XÁC NHẬN MẬT KHẨU</label>
                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" required>
            </div>

            <button type="submit" class="btn btn-custom btn-block shadow">
                TẠO TÀI KHOẢN
            </button>
        </form>

        <div class="text-center mt-4">
            <span class="text-muted small">Đã có tài khoản?</span>
            <a href="{{ route('login') }}" class="small font-weight-bold ml-1 text-primary">Đăng nhập</a>
        </div>
    </div>
</div>
@endsection