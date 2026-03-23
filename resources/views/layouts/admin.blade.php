<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Bãi Xe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background-color: #336699; }
        .nav-link { color: white !important; }
        .dropdown-menu { border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        /* CSS ép Tab nằm cùng 1 hàng */
        .nav-tabs { display: flex !important; flex-wrap: nowrap !important; white-space: nowrap !important; }
        .nav-tabs .nav-item { margin-bottom: -1px; }
        .nav-tabs .nav-link { font-size: 13px; padding: 10px 15px; color: #555; background: #eee; border: 1px solid #ddd; }
        .nav-tabs .nav-link.active { background: #fff !important; color: #333 !important; font-weight: bold; border-bottom-color: #fff !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand text-white font-weight-bold" href="/"><i class="fas fa-home"></i> Trang chủ</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown">
                            <i class="fas fa-list"></i> Danh mục hệ thống
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('employee.index') }}">Quản lý Tài khoản / Nhân viên</a>
                            <a class="dropdown-item" href="{{ route('price.index') }}">Quản lý giá xe</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('parking.index') }}"><i class="fas fa-camera"></i> Nhận diện biển số xe</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto text-white align-items-center">
                    <li class="nav-item mr-3"><i class="fas fa-user-circle"></i> admin</li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="{{ route('login') }}"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>