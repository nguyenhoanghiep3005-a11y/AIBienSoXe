@extends('admin')

@section('title', 'Nhận diện & Lượt xe')

@section('content')
<!-- Header của trang và Thanh tìm kiếm -->
<div class="row align-items-center mb-4 mt-2">
    <div class="col-md-6">
        <h3 class="text-secondary font-weight-bold text-uppercase mb-0">
            <i class="fas fa-microchip text-primary me-2"></i> Lượt xe ra vào
        </h3>
    </div>
    <!-- Cụm tìm kiếm biển số -->
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <form class="d-inline-flex" method="GET" action="{{ route('parking.index') }}">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" name="plate" class="form-control border-start-0" placeholder="Tìm biển số xe..." value="{{ request('plate') }}" style="min-width: 200px;">
                <button type="submit" class="btn btn-dark fw-bold">Tìm kiếm</button>
            </div>
            @if(request('plate'))
                <a href="{{ route('parking.index') }}" class="btn btn-outline-danger ms-2" title="Hủy tìm kiếm">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>
    </div>
</div>

<!-- KHU VỰC CAMERA / NHẬN DIỆN BIỂN SỐ -->
<div class="row mb-4">
    <!-- Cổng vào (Check-in) -->
    <div class="col-md-6 mb-3">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-primary text-white fw-bold text-uppercase py-3">
                <i class="fas fa-arrow-right-to-bracket me-2"></i> Làn xe vào (Check-in)
            </div>
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <label for="image-in" class="form-label text-muted small fw-bold">CHỌN ẢNH HOẶC CHỤP HÌNH XE VÀO</label>
                    <input class="form-control form-control-lg border-dashed" type="file" id="image-in" accept="image/*" capture="environment">
                </div>
                
                <!-- Vùng hiển thị ảnh xem trước -->
                <div id="preview-in" class="mb-4 d-none">
                    <img src="" alt="Xem trước ảnh vào" class="img-fluid rounded shadow-sm border p-1 bg-white" style="max-height: 250px; object-fit: contain; width: 100%;">
                </div>

                <button class="btn btn-primary btn-lg w-100 fw-bold shadow-sm py-3" onclick="handleParking('in')">
                    <i class="fas fa-camera me-2"></i> QUÉT BIỂN SỐ VÀO
                </button>
            </div>
        </div>
    </div>

    <!-- Cổng ra (Check-out) -->
    <div class="col-md-6 mb-3">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-danger text-white fw-bold text-uppercase py-3">
                <i class="fas fa-arrow-right-from-bracket me-2"></i> Làn xe ra (Check-out)
            </div>
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <label for="image-out" class="form-label text-muted small fw-bold">CHỌN ẢNH HOẶC CHỤP HÌNH XE RA</label>
                    <input class="form-control form-control-lg border-dashed" type="file" id="image-out" accept="image/*" capture="environment">
                </div>
                
                <!-- Vùng hiển thị ảnh xem trước -->
                <div id="preview-out" class="mb-4 d-none">
                    <img src="" alt="Xem trước ảnh ra" class="img-fluid rounded shadow-sm border p-1 bg-white" style="max-height: 250px; object-fit: contain; width: 100%;">
                </div>

                <button class="btn btn-danger btn-lg w-100 fw-bold shadow-sm py-3" onclick="handleParking('out')">
                    <i class="fas fa-camera me-2"></i> QUÉT BIỂN SỐ RA
                </button>
            </div>
        </div>
    </div>
</div>

<!-- BẢNG LỊCH SỬ RA VÀO -->
<div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <span class="fw-bold text-dark">
            <i class="fas fa-history text-secondary me-2"></i> LỊCH SỬ XE RA VÀO MỚI NHẤT
        </span>
        <span class="badge bg-light text-dark border fw-normal">Thời gian thực</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-center align-middle" id="parking-history-table">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th style="width: 100px;">Minh họa</th>
                        <th>Biển số</th>
                        <th>Loại xe</th>
                        <th>Thời gian vào</th>
                        <th>Thời gian ra</th>
                        <th>Trạng thái</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($logs ?? [] as $log)
                    <tr>
                        <td>
                            <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 45px; margin: auto;">
                                <i class="fas fa-image"></i>
                            </div>
                        </td>
                        <td class="fw-bold fs-5 text-primary">{{ $log->plate_number }}</td>
                        <td>{{ $log->vehicleType->name ?? 'Đang xác định' }}</td>
                        <td class="small text-muted">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('d/m/Y H:i') : '-' }}</td>
                        <td class="small text-muted">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if($log->status == 'in')
                                <span class="badge bg-primary px-3 py-2 shadow-sm"><i class="fas fa-sign-in-alt me-1"></i> Đang đỗ</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 opacity-75"><i class="fas fa-sign-out-alt me-1"></i> Đã ra</span>
                            @endif
                        </td>
                        <td class="{{ $log->fee ? 'text-danger fw-bold fs-6' : 'text-muted' }}">
                            {{ $log->fee ? number_format($log->fee) . ' đ' : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="7" class="text-muted py-5 text-center bg-light bg-opacity-25">
                            <i class="fas fa-inbox fa-3x mb-3 text-light"></i><br>
                            <span class="fs-6 fw-light">Chưa có dữ liệu lịch sử xe ra vào.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Phân trang (Nếu có) -->
    @if(isset($logs) && method_exists($logs, 'links') && $logs->hasPages())
    <div class="card-footer bg-white d-flex justify-content-center pt-3 border-top-0">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- ========================================================= -->
<!-- TEMPLATE ẨN DÙNG CHO JAVASCRIPT THÊM DÒNG MỚI (KHÔNG HIỂN THỊ) -->
<!-- ========================================================= -->
<template id="history-row-template">
    <tr class="table-success" style="transition: all 2s ease;">
        <td>
            <div class="bg-success text-white rounded d-flex align-items-center justify-content-center shadow-sm animate-pulse" style="width: 70px; height: 45px; margin: auto;">
                <i class="fas fa-check"></i>
            </div>
        </td>
        <td class="col-plate fw-bold fs-5 text-primary"></td>
        <td class="col-type text-dark"></td>
        <td class="col-time-in small text-muted"></td>
        <td class="col-time-out small text-muted"></td>
        <td class="col-status"></td>
        <td class="col-fee"></td>
    </tr>
</template>

<style>
    /* Hiệu ứng nhẹ khi chọn file */
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    /* Hiệu ứng nháy cho dòng mới */
    .animate-pulse { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endsection