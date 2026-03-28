@extends('layouts.admin')

@section('title', 'Quản lý Phương tiện & Cấu hình giá')

@section('content')
<!-- Header của trang -->
<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h3 class="text-secondary font-weight-bold text-uppercase">
        <i class="fas fa-car text-dark me-2"></i> Quản lý Phương tiện
    </h3>
    <button class="btn btn-primary shadow-sm">
        <i class="fas fa-plus"></i> Thêm cấu hình giá
    </button>
</div>

<!-- Bảng dữ liệu -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>STT</th>
                        <th>Loại xe</th>
                        <th>Khung giờ</th>
                        <th>Thời gian</th>
                        <th>Mức giá</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Ví dụ vòng lặp Foreach render dữ liệu từ Controller truyền sang --}}
                    {{-- @foreach($priceConfigs as $index => $config) --}}
                    
                    {{-- Dữ liệu mẫu tĩnh --}}
                    <tr>
                        <td>1</td>
                        <td class="font-weight-bold text-primary">Xe máy</td>
                        <td>Ban ngày</td>
                        <td>06:00 - 18:00</td>
                        <td class="text-success font-weight-bold">5,000 đ</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" title="Sửa"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td class="font-weight-bold text-primary">Ô tô 4-7 chỗ</td>
                        <td>Theo giờ</td>
                        <td>00:00 - 23:59</td>
                        <td class="text-success font-weight-bold">20,000 đ/giờ</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" title="Sửa"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection