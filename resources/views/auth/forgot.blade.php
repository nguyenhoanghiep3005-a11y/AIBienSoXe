@extends('layouts.auth')

@section('content')
<div class="card glass-card border-0">
    <div class="card-body p-4 p-md-5 text-center">
        <div class="mb-4">
            <div class="d-inline-block p-3 bg-light rounded-circle mb-3">
                <i class="fas fa-lock-open fa-2x text-primary"></i>
            </div>
            <h3 class="font-weight-bold text-dark">QUÊN MẬT KHẨU?</h3>
            <p class="text-muted small">Nhập email đăng ký để nhận mã khôi phục</p>
        </div>
        
        <form class="text-left">
            <div class="form-group mb-4">
                <label class="small font-weight-bold">ĐỊA CHỈ EMAIL</label>
                <input type="email" class="form-control" placeholder="example@gmail.com" required>
            </div>

            <button type="submit" class="btn btn-custom btn-block shadow">
                GỬI MÃ XÁC NHẬN
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('login') }}" class="small font-weight-bold text-secondary text-decoration-none">
                <i class="fas fa-arrow-left mr-1"></i> Quay lại đăng nhập
            </a>
        </div>
    </div>
</div>
@endsection