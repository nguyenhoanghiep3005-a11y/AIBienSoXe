<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Token bảo mật bắt buộc cho AJAX POST trong Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Hệ thống Quản lý Bãi Xe')</title>
    
    <!-- Bootstrap CSS 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .cursor-pointer { cursor: pointer; }
        .hover-card { transition: all 0.3s ease; }
        .hover-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; 
        }
        
        /* Hiệu ứng cho menu active */
        .navbar-nav .nav-link { font-weight: 500; transition: 0.3s; }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            color: #0d6efd !important;
        }
    </style>
</head>
<body>

    <!-- Thanh điều hướng (Navbar) nằm trên cùng -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-sm sticky-top">
        <div class="container">
            <!-- Logo / Brand -->
            <a class="navbar-brand font-weight-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-parking text-primary fs-4 me-1"></i> 
                <span class="fs-5 tracking-wide">SmartParking</span>
            </a>
            
            <!-- Nút menu trên Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Danh sách menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('price.*') ? 'active' : '' }}" href="{{ route('price.index') }}">
                            <i class="fas fa-car me-1"></i> Phương tiện & Giá
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parking.*') ? 'active' : '' }}" href="{{ route('parking.index') }}">
                            <i class="fas fa-microchip me-1"></i> Nhận diện camera
                        </a>
                    </li>
                </ul>
                
                <!-- Góc phải (Tài khoản) -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle fs-5 me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm me-2"></i> Hồ sơ</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-sign-out-alt fa-sm me-2"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nội dung chính được nạp từ các file con (dashboard, parking, price...) -->
    <div class="container flex-grow-1">
        @yield('content')
    </div>

    <!-- Footer nhẹ nhàng (Tùy chọn) -->
    <footer class="text-center text-muted py-4 mt-auto">
        <small>&copy; {{ date('Y') }} Smart Parking System. Thiết kế bởi Nguyen Hoang Hiep.</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- File Javascript dùng chung cho hệ thống (đổi thành custom.js nếu bạn đang dùng custom.js trong máy) -->
    <script src="{{ asset('js/custom.js') }}"></script>
    
    <!-- Stack để chèn thêm Javascript riêng cho từng trang -->
    @stack('scripts')
</body>
</html>