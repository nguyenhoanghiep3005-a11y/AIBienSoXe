@extends('layouts.admin')

@section('title', 'Nhận diện Biển số & Lượt xe')

@section('content')
<!-- Header của trang -->
<div class="row align-items-center mb-4 mt-2">
    <div class="col-md-6">
        <h3 class="text-secondary font-weight-bold text-uppercase">
            <i class="fas fa-microchip text-dark me-2"></i> Lượt xe ra vào
        </h3>
    </div>
    <!-- Cụm tìm kiếm -->
    <div class="col-md-6 text-md-end">
        <form class="d-inline-flex" method="GET" action="#">
            <input type="text" class="form-control me-2" placeholder="Nhập biển số xe..." style="max-width: 250px;">
            <button type="submit" class="btn btn-dark"><i class="fas fa-search"></i> Tìm</button>
        </form>
    </div>
</div>

<!-- Bảng dữ liệu -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center align-middle">
                <thead class="bg-light text-dark">
                    <tr>
                        <th>Ảnh Camera</th>
                        <th>Biển số</th>
                        <th>Loại xe</th>
                        <th>Giờ vào</th>
                        <th>Giờ ra</th>
                        <th>Trạng thái</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dữ liệu mẫu tĩnh --}}
                    <tr>
                        <td>
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 50px; margin: auto;">
                                <i class="fas fa-camera"></i>
                            </div>
                        </td>
                        <td class="font-weight-bold h5">59-A1 123.45</td>
                        <td>Xe máy</td>
                        <td>28/03/2026 08:30</td>
                        <td>-</td>
                        <td><span class="badge bg-primary">Đang đỗ</span></td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 50px; margin: auto;">
                                <i class="fas fa-camera"></i>
                            </div>
                        </td>
                        <td class="font-weight-bold h5">30-G 999.99</td>
                        <td>Ô tô</td>
                        <td>28/03/2026 09:15</td>
                        <td>28/03/2026 11:15</td>
                        <td><span class="badge bg-secondary">Đã ra</span></td>
                        <td class="text-danger font-weight-bold">40,000 đ</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection