@extends('admin')

@section('title', 'Nhận diện Biển số')

@php
    $statusLabels = [
        'parking' => 'Đang gửi',
        'completed' => 'Đã ra',
    ];
@endphp

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-dark fw-bold mb-2"><i class="fas fa-camera text-primary me-2"></i>Nhận diện Biển số</h2>
    </div>
    <form method="GET" action="{{ route('parking.index') }}" class="d-flex gap-2">
        <input type="text" name="plate" class="form-control" placeholder="Tìm lại biển số xe..." value="{{ request('plate') }}">
        <button class="btn btn-outline-secondary">Tìm</button>
    </form>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-6">
        <div class="app-card p-4 parking-panel" data-type="in" data-save-url="{{ route('parking.checkin') }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h4 text-primary mb-0"><i class="fas fa-right-to-bracket me-2"></i>Xe vào</h3>
                <span class="badge text-bg-primary">Check-in</span>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Chọn ảnh hoặc chụp xe vào</label>
                <input type="file" class="form-control parking-file-input" accept="image/*" capture="environment">
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-outline-primary start-camera-btn"><i class="fas fa-video me-2"></i>Mở camera</button>
                <button type="button" class="btn btn-outline-secondary capture-camera-btn d-none"><i class="fas fa-circle-check me-2"></i>Xác nhận ảnh</button>
            </div>

            <div class="camera-wrap d-none mb-3">
                <video class="w-100 rounded-4 border parking-video" autoplay playsinline muted></video>
                <div class="small text-muted mt-2">Đưa biển số vào giữa khung rồi bấm "Xác nhận ảnh".</div>
            </div>

            <div class="preview-wrap d-none mb-3">
                <img class="preview-image img-fluid rounded-4 border" alt="preview">
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-primary recognize-btn w-100"><i class="fas fa-car-side me-2"></i>Nhận diện biển số</button>
            </div>

            <div class="recognition-result d-none border rounded-4 p-3 bg-light-subtle">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Biển số</label>
                        <input type="text" class="form-control recognized-plate-input" placeholder="Ví dụ: 51G-100.96">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Loại xe</label>
                        <select class="form-select vehicle-type-select">
                            <option value="">Chưa chọn</option>
                            @foreach($vehicleTypes as $vehicleType)
                                <option value="{{ $vehicleType->id }}">{{ $vehicleType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-note-btn">
                        <i class="fas fa-note-sticky me-2"></i>Ghi chú
                    </button>
                    <div class="note-wrap d-none mt-3">
                        <textarea class="form-control note-input" rows="3" placeholder="Nhập ghi chú nếu cần..."></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="small text-muted mb-2 live-time-label">Thời gian thực: --:--:--</div>
                    <label class="form-label fw-semibold">Thời gian xe vào</label>
                    <input type="datetime-local" class="form-control time-input">
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-success save-btn"><i class="fas fa-floppy-disk me-2"></i>Lưu xe vào</button>
                    <button type="button" class="btn btn-light reset-btn">Làm lại</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="app-card p-4 parking-panel" data-type="out" data-save-url="{{ route('parking.checkout') }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h4 text-danger mb-0"><i class="fas fa-right-from-bracket me-2"></i>Xe ra</h3>
                <span class="badge text-bg-danger">Check-out</span>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Chọn ảnh hoặc chụp xe ra</label>
                <input type="file" class="form-control parking-file-input" accept="image/*" capture="environment">
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="btn btn-outline-danger start-camera-btn"><i class="fas fa-video me-2"></i>Mở camera</button>
                <button type="button" class="btn btn-outline-secondary capture-camera-btn d-none"><i class="fas fa-circle-check me-2"></i>Xác nhận ảnh</button>
            </div>

            <div class="camera-wrap d-none mb-3">
                <video class="w-100 rounded-4 border parking-video" autoplay playsinline muted></video>
                <div class="small text-muted mt-2">Đưa biển số vào giữa khung rồi bấm "Xác nhận ảnh".</div>
            </div>

            <div class="preview-wrap d-none mb-3">
                <img class="preview-image img-fluid rounded-4 border" alt="preview">
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-danger recognize-btn w-100"><i class="fas fa-car-side me-2"></i>Nhận diện biển số</button>
            </div>

            <div class="recognition-result d-none border rounded-4 p-3 bg-light-subtle">
                <input type="hidden" class="recognized-source-input">
                <input type="hidden" class="matched-log-id-input">

                <div class="mb-3 p-3 rounded-3 bg-white border matched-log-box d-none"></div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Biển số</label>
                        <input type="text" class="form-control recognized-plate-input" placeholder="Bạn có thể sửa biển số trước khi lưu">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-note-btn">
                        <i class="fas fa-note-sticky me-2"></i>Ghi chú
                    </button>
                    <div class="note-wrap d-none mt-3">
                        <textarea class="form-control note-input" rows="3" placeholder="Nhập ghi chú khi xe ra..."></textarea>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="small text-muted mb-2 live-time-label">Thời gian thực: --:--:--</div>
                    <label class="form-label fw-semibold">Thời gian xe ra</label>
                    <input type="datetime-local" class="form-control time-input">
                </div>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-success save-btn"><i class="fas fa-floppy-disk me-2"></i>Lưu xe ra</button>
                    <button type="button" class="btn btn-light reset-btn">Làm lại</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="app-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="h4 text-dark mb-1">Danh sách biển số</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover" id="parking-history-table">
            <thead>
                <tr class="text-uppercase small text-muted">
                    <th>Biển số</th>
                    <th>Loại xe</th>
                    <th>Giờ vào</th>
                    <th>Giờ ra</th>
                    <th>Trạng thái</th>
                    <th>Phí</th>
                    <th>Ghi chú</th>
                    <th class="text-end">Xem lại</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr class="history-row">
                        <td class="fw-bold text-primary col-plate">{{ $log->plate_number }}</td>
                        <td class="col-type">{{ $log->vehicleType?->name ?? 'Chưa chọn' }}</td>
                        <td class="col-time-in">{{ optional($log->time_in)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="col-time-out">{{ optional($log->time_out)->format('d/m/Y H:i') ?? '-' }}</td>
                        <td class="col-status">
                            <span class="badge {{ $log->status === 'parking' ? 'text-bg-primary' : 'text-bg-secondary' }}">
                                {{ $statusLabels[$log->status] ?? $log->status }}
                            </span>
                        </td>
                        <td class="col-fee">{{ $log->total_price ? number_format($log->total_price, 0, ',', '.') . ' đ' : '-' }}</td>
                        <td class="col-note text-muted">{{ $log->note ? \Illuminate\Support\Str::limit($log->note, 35) : '-' }}</td>
                        <td class="text-end">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-dark history-view-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#historyDetailModal"
                                data-plate="{{ $log->plate_number }}"
                                data-vehicle-type="{{ $log->vehicleType?->name ?? 'Chưa chọn' }}"
                                data-time-in="{{ optional($log->time_in)->format('d/m/Y H:i') ?? '-' }}"
                                data-time-out="{{ optional($log->time_out)->format('d/m/Y H:i') ?? '-' }}"
                                data-status="{{ $statusLabels[$log->status] ?? $log->status }}"
                                data-fee="{{ $log->total_price ? number_format($log->total_price, 0, ',', '.') . ' đ' : '-' }}"
                                data-note="{{ $log->note }}"
                                data-image-in="{{ $log->image_in ? asset($log->image_in) : '' }}"
                                data-image-out="{{ $log->image_out ? asset($log->image_out) : '' }}"
                            >
                                <i class="fas fa-eye me-1"></i>Xem
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8" class="text-center py-5 text-muted">Chưa có dữ liệu xe vào ra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="pt-3">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<template id="history-row-template">
    <tr class="history-row table-success">
        <td class="fw-bold text-primary col-plate"></td>
        <td class="col-type"></td>
        <td class="col-time-in"></td>
        <td class="col-time-out"></td>
        <td class="col-status"></td>
        <td class="col-fee"></td>
        <td class="col-note text-muted"></td>
        <td class="text-end col-actions"></td>
    </tr>
</template>

<div class="modal fade" id="historyDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">Chi tiết biển số</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-1">Biển số</div>
                            <div class="fw-bold fs-4" id="detail-plate">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-1">Loại xe</div>
                            <div class="fw-semibold fs-5" id="detail-vehicle-type">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-1">Giờ vào</div>
                            <div id="detail-time-in">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-1">Giờ ra</div>
                            <div id="detail-time-out">-</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-1">Phí</div>
                            <div id="detail-fee">-</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="text-muted small mb-2">Trạng thái</div>
                    <div id="detail-status" class="fw-semibold">-</div>
                </div>

                <div class="mb-4">
                    <div class="text-muted small mb-2">Ghi chú</div>
                    <div id="detail-note" class="border rounded-4 p-3 bg-light-subtle">Không có ghi chú.</div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-2">Ảnh xe vào</div>
                            <img id="detail-image-in" class="img-fluid rounded-3 d-none" alt="Ảnh xe vào">
                            <div id="detail-image-in-empty" class="text-muted">Chưa có ảnh xe vào.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-4 p-3 h-100">
                            <div class="text-muted small mb-2">Ảnh xe ra</div>
                            <img id="detail-image-out" class="img-fluid rounded-3 d-none" alt="Ảnh xe ra">
                            <div id="detail-image-out-empty" class="text-muted">Chưa có ảnh xe ra.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
