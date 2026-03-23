<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ Thống Quản Lý Bãi Xe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background-color: #f4f7f6; margin: 0; }
        .navbar-custom { background-color: #336699; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-link { color: white !important; font-weight: 600; margin-right: 15px; }
        .container-fluid { padding: 20px 30px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top mb-4">
        <div class="container-fluid">
            <a class="navbar-brand text-white font-weight-bold" href="/"><i class="fas fa-parking mr-2"></i> TRANG CHỦ</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="/parking"><i class="fas fa-camera mr-1"></i> NHẬN DIỆN BIỂN SỐ</a></li>
                </ul>
                <div class="text-white small"><i class="fas fa-user-shield"></i> admin | <a href="/login" class="text-white ml-2">Đăng xuất</a></div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>