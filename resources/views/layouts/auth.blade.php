<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Bãi Xe</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Quicksand', sans-serif;
            /* Thay link dưới bằng đường dẫn ảnh image_572c48.jpg của bạn */
            background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55)), 
                        url('https://images.unsplash.com/photo-1506521781263-d8422e82f27a?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 420px; /* Khống chế độ rộng để không bị tràn */
            padding: 15px;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            overflow: hidden; /* Chống nội dung bên trong tràn ra */
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            height: auto;
            border: 1px solid #ddd;
            background: #fdfdfd;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(51, 102, 153, 0.25);
            border-color: #336699;
        }
        .btn-custom {
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            background: linear-gradient(to right, #336699, #4a90e2);
            border: none;
            color: white;
            transition: 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            color: white;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>