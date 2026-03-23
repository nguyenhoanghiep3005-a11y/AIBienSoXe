@extends('layouts.auth')

@section('content')
<div class="row justify-content-center">
    <div class="card auth-card shadow-lg border-0" style="border-radius: 20px;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <i class="fas fa-parking fa-3x text-primary"></i>
                <h3 class="font-weight-bold mt-3">ĐĂNG NHẬP</h3>
            </div>
            
            <form action="#" method="POST">
                <div class="form-group mb-4">
                    <label class="small font-weight-bold">EMAIL</label>
                    <input type="email" class="form-control form-control-lg bg-light border-0" placeholder="example@gmail.com" required style="border-radius: 10px;">
                </div>
                
                <div class="form-group mb-2">
                    <label class="small font-weight-bold">MẬT KHẨU</label>
                    <input type="password" class="form-control form-control-lg bg-light border-0" placeholder="********" required style="border-radius: 10px;">
                </div>

                <div class="text-right mb-4">
                    <a href="{{ route('password.request') }}" class="small text-muted">Quên mật khẩu?</a>
                </div>

                  <button type="submit" class="btn btn-custom btn-block shadow">
                Đăng Nhập
            </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="small font-weight-bold ml-1">Đăng ký</a>
            </div>
        </div>
    </div>
</div>
@endsection