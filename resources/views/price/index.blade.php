@extends('admin')

@section('title', 'Phương tiện & Giá')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-dark fw-bold mb-2"><i class="fas fa-tags text-primary me-2"></i>Danh mục & Bảng giá</h2>
    </div>
    <button class="btn btn-primary btn-lg rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#priceConfigModal" data-mode="create">
        <i class="fas fa-plus-circle me-2"></i>Thêm cấu hình mới
    </button>
</div>

<div class="app-card p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <h3 class="h4 text-dark mb-0">Bảng cấu hình hiện tại</h3>
        <form method="GET" action="{{ route('price.index') }}" class="d-flex gap-2">
            <select name="vehicle_type_id" class="form-select">
                <option value="">Tất cả loại xe</option>
                @foreach($vehicleTypes as $vehicleType)
                    <option value="{{ $vehicleType->id }}" @selected((string) $selectedVehicleTypeId === (string) $vehicleType->id)>{{ $vehicleType->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary">Lọc</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead>
                <tr class="text-uppercase small text-muted">
                    <th>STT</th>
                    <th>Loại phương tiện</th>
                    <th>Tên khung giờ</th>
                    <th>Thời gian áp dụng</th>
                    <th>Mức giá thu</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($configs as $config)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold text-primary">{{ $config->vehicleType?->name ?? 'Chưa gán' }}</td>
                        <td>{{ $config->time_block_name }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $config->start_time)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $config->end_time)->format('H:i') }}</td>
                        <td class="fw-bold text-success">{{ number_format($config->price, 0, ',', '.') }} đ</td>
                        <td class="text-end">
                            <button
                                type="button"
                                class="btn btn-sm btn-edit me-2"
                                data-bs-toggle="modal"
                                data-bs-target="#priceConfigModal"
                                data-mode="edit"
                                data-id="{{ $config->id }}"
                                data-vehicle-type-id="{{ $config->vehicle_type_id }}"
                                data-time-block-name="{{ $config->time_block_name }}"
                                data-start-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $config->start_time)->format('H:i') }}"
                                data-end-time="{{ \Carbon\Carbon::createFromFormat('H:i:s', $config->end_time)->format('H:i') }}"
                                data-price="{{ (float) $config->price }}"
                            >
                                <i class="fas fa-pen"></i>
                            </button>
                            <form action="{{ route('price.destroy', $config) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Chưa có cấu hình giá nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="modal fade" id="priceConfigModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="POST" id="price-config-form">
                @csrf
                <div id="price-config-method"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="price-config-title">Thêm cấu hình giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Loại phương tiện</label>
                        <select class="form-select" name="vehicle_type_id" id="price-vehicle-type-id" required>
                            <option value="">Chọn loại xe</option>
                            @foreach($vehicleTypes as $vehicleType)
                                <option value="{{ $vehicleType->id }}">{{ $vehicleType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên khung giờ</label>
                        <input type="text" class="form-control" name="time_block_name" id="price-time-block-name" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Bắt đầu</label>
                            <input type="time" class="form-control" name="start_time" id="price-start-time" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kết thúc</label>
                            <input type="time" class="form-control" name="end_time" id="price-end-time" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Mức giá</label>
                        <input type="number" step="1000" min="0" class="form-control" name="price" id="price-price" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu cấu hình</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('priceConfigModal');
    if (!modal) return;

    const form = document.getElementById('price-config-form');
    const methodHolder = document.getElementById('price-config-method');
    const title = document.getElementById('price-config-title');
    const vehicleTypeId = document.getElementById('price-vehicle-type-id');
    const timeBlockName = document.getElementById('price-time-block-name');
    const startTime = document.getElementById('price-start-time');
    const endTime = document.getElementById('price-end-time');
    const price = document.getElementById('price-price');
    const storeUrl = @json(route('price.store'));
    const updateUrlTemplate = @json(url('/price/__ID__'));

    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const mode = button?.dataset.mode ?? 'create';

        form.action = storeUrl;
        methodHolder.innerHTML = '';
        title.textContent = 'Thêm cấu hình giá';
        vehicleTypeId.value = '';
        timeBlockName.value = '';
        startTime.value = '';
        endTime.value = '';
        price.value = '';

        if (mode === 'edit') {
            title.textContent = 'Chỉnh sửa cấu hình giá';
            form.action = updateUrlTemplate.replace('__ID__', button.dataset.id);
            methodHolder.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            vehicleTypeId.value = button.dataset.vehicleTypeId;
            timeBlockName.value = button.dataset.timeBlockName;
            startTime.value = button.dataset.startTime;
            endTime.value = button.dataset.endTime;
            price.value = button.dataset.price;
        }
    });
});
</script>
@endpush
