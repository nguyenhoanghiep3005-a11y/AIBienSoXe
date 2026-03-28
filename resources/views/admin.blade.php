<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app-theme.css') }}">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/smart-parking-logo.svg') }}" alt="NHH Logo" style="width: 48px; height: 48px;">
            <span>HOANG HIEP</span>
        </div> 
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-house"></i>
                    <span>Trang chủ</span>
                </a>
            </li>
            <li>
                <a href="{{ route('price.index') }}" class="{{ request()->routeIs('price.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Phương tiện & Giá</span>
                </a>
            </li>
            <li>
                <a href="{{ route('parking.index') }}" class="{{ request()->routeIs('parking.*') ? 'active' : '' }}">
                    <i class="fas fa-camera"></i>
                    <span>Nhận diện Biển số</span>
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <nav class="top-navbar shadow-sm">
            <h1 class="page-title">Hệ Thống Quản Lý Bãi Đỗ Xe</h1>
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-body shadow-none" id="theme-toggle" title="Chuyển chế độ sáng tối">
                    <i class="fas fa-moon fs-4"></i>
                </button>
            </div>
        </nav>

        <main class="content-shell">
            @yield('content')
        </main>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-4" id="app-toast-container"></div>

    <script>
        window.APP_FLASH = {
            status: @json(session('status')),
            errors: @json($errors->all())
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('scripts')
</body>
</html>
