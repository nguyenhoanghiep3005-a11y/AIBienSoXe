@extends('admin')

@section('title', 'Trang chủ')

@section('content')
<section class="row justify-content-center g-4">
    <div class="col-xl-5 col-lg-6 col-md-10">
        <a href="{{ route('price.index') }}" class="text-decoration-none d-block h-100">
            <div class="app-card h-100 p-5 text-center">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 96px; height: 96px; background: rgba(111, 131, 80, 0.16); color: #6f8350;">
                        <i class="fas fa-tags fa-2x"></i>
                    </span>
                    <h2 class="text-dark mb-3" style="font-size: 2.15rem;">Phương tiện & Giá</h2>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 460px; font-size: 1.45rem; line-height: 1.7;">
                        Quản lý loại xe, khung giờ thu phí và mức giá áp dụng cho từng nhóm phương tiện.
                    </p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-xl-5 col-lg-6 col-md-10">
        <a href="{{ route('parking.index') }}" class="text-decoration-none d-block h-100">
            <div class="app-card h-100 p-5 text-center">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 96px; height: 96px; background: rgba(179, 92, 91, 0.12); color: var(--accent);">
                        <i class="fas fa-camera fa-2x"></i>
                    </span>
                    <h2 class="text-dark mb-3" style="font-size: 2.15rem;">Nhận diện Biển số</h2>
                    <p class="text-muted mb-0 mx-auto" style="max-width: 460px; font-size: 1.45rem; line-height: 1.7;">
                        Quét bằng camera hoặc file, xác nhận ảnh, nhận diện biển số và lưu danh sách xe ra vào.
                    </p>
                </div>
            </div>
        </a>
    </div>
</section>
@endsection
