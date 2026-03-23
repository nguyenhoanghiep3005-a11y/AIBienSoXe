@extends('layouts.admin')

@section('content')
<style>
   /* TỔNG THỂ THANH TAB HIỆN ĐẠI */
.nav-tabs {
    display: flex !important;
    flex-wrap: nowrap !important;
    border-bottom: 2px solid #ebeef5; /* Đường gạch dưới thanh mảnh */
    margin-bottom: 25px;
}

.nav-tabs .nav-item {
    margin-bottom: -2px; /* Đè lên đường border của tổng thể */
}

.nav-tabs .nav-link {
    font-size: 14px;
    font-weight: 600;
    color: #909399 !important; /* Màu xám trung tính cho tab chưa chọn */
    background: #f5f7fa; /* Nền xám rất nhạt */
    border: 1px solid #e4e7ed;
    border-radius: 8px 8px 0 0; /* Bo góc trên */
    padding: 12px 20px;
    margin-right: 8px;
    transition: all 0.3s ease; /* Hiệu ứng mượt khi di chuột */
    white-space: nowrap;
}

/* KHI DI CHUỘT QUA TAB */
.nav-tabs .nav-link:hover {
    color: #336699 !important;
    background: #edf2f7;
    border-color: #dcdfe6;
}

/* TAB ĐANG ĐƯỢC CHỌN (ACTIVE) */
.nav-tabs .nav-link.active {
    color: #ffffff !important; /* Chữ trắng trên nền xanh */
    background: linear-gradient(135deg, #336699, #4a90e2) !important; /* Đổ màu gradient xanh */
    border-color: #336699 !important;
    box-shadow: 0 4px 12px rgba(51, 102, 153, 0.3); /* Đổ bóng cho tab nổi bật */
}

/* Fix lỗi chữ tàng hình trên Tab chưa chọn */
#home-tab:not(.active) {
    color: #5a5e66 !important;
}
</style>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="bg-white p-3 border rounded shadow-sm">
            <ul class="nav nav-tabs" id="parkingTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="camera-tab" data-toggle="tab" href="#camera-content" role="tab">Nhận diện Camera</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="image-tab" data-toggle="tab" href="#image-content" role="tab">Nhận diện biển số xe bằng hình ảnh</a>
                </li>
            </ul>

            <div class="mt-3">
                <label class="font-weight-bold mb-1">Chọn thông tin xe:</label>
                <select class="form-control">
                    <option>Xe máy - Sáng - 5000 (05:00 - 12:59)</option>
                    <option>Ô tô - Đêm - 300000 (00:00 - 04:59)</option>
                </select>
            </div>

            <div class="group-border">
                <span class="group-title">Thông tin nhận diện biển số xe thủ công</span>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="camera-content" role="tabpanel">
                        <div class="border bg-light mb-3" style="height: 250px; display: flex; align-items: center; justify-content: center;">
                            <img src="https://via.placeholder.com/400x300?text=Camera+Preview" class="img-fluid" style="max-height: 100%;">
                        </div>
                        <label class="small">Chọn hình thức nhận diện:</label>
                        <select class="form-control mb-2">
                            <option>Nhận diện bằng camera laptop</option>
                        </select>
                        <button class="btn btn-success font-weight-bold">Bắt đầu</button>
                        <button class="btn btn-danger font-weight-bold ml-1">Tắt Camera</button>
                    </div>

                    <div class="tab-pane fade" id="image-content" role="tabpanel">
                        <label class="small">Chọn hình ảnh biển số:</label>
                        <input type="file" class="form-control-file border p-2 mb-2">
                        <button class="btn btn-info text-white font-weight-bold"><i class="fas fa-search"></i> Nhận diện biển số</button>
                        <div class="border mt-2 bg-light text-center" style="height: 200px;">
                            <img src="https://via.placeholder.com/400x300?text=Selected+Image" class="img-fluid h-100">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="small mb-1">Ghi chú (nếu không nhận dạng được):</label>
                    <input type="text" class="form-control mb-3">
                    <label class="small mb-1">Biển số vừa nhận diện được:</label>
                    <input type="text" class="form-control border-primary text-primary font-weight-bold" style="background: #eef;" value="29C1-999.99" >
                    <button class="btn btn-warning mt-3 font-weight-bold text-white w-100">XÁC NHẬN VÀ LƯU</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="bg-white border rounded shadow-sm overflow-hidden">
            <table class="table table-bordered mb-0 text-center table-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Loại xe</th>
                        <th>Biển số</th>
                        <th>Vào</th>
                        <th>Ra</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ô tô</td>
                        <td><div class="plate-box">51F70804</div><br><small>Chi tiết</small></td>
                        <td>28/07/24<br>11:28</td>
                        <td>--</td>
                        <td>150k</td>
                        <td><span class="status-badge bg-orange">Đang gửi</span></td>
                    </tr>
                    <tr>
                        <td>Xe máy</td>
                        <td><div class="plate-box">61T3-2222</div><br><small>Chi tiết</small></td>
                        <td>28/07/24<br>11:26</td>
                        <td>28/07/24<br>11:27</td>
                        <td>5k</td>
                        <td><span class="status-badge bg-blue">Đã trả</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection