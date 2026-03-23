<div class="container-fluid">
    <div class="row">
        <div class="col-md-5">
            <div class="card card-success">
                <div class="card-header">Thông tin nhận dạng biển số</div>
                <div class="card-body">
                    <form action="{{ route('parking.recognize') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Chọn thông tin xe:</label>
                            <select class="form-control" name="config_id">
                                <option value="1">Xe máy - Sáng - 5000 (05:00 - 12:59)</option>
                            </select>
                        </div>
                        
                        <div class="border p-3 mt-3 text-danger">
                            <h5>Thông tin nhận diện biển số xe thủ công</h5>
                            <input type="file" name="plate_image" class="form-control-file">
                            <button type="submit" class="btn btn-info mt-3">Nhận diện biển số</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">Danh sách biển số</div>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Loại xe</th>
                                <th>Biển số</th>
                                <th>Thời gian vào</th>
                                <th>Thông tin giá</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->vehicle_type }}</td>
                                <td class="text-success"><strong>{{ $log->license_plate }}</strong></td>
                                <td>{{ $log->time_in }}</td>
                                <td><span class="badge badge-warning">{{ $log->price }} / Lượt</span></td>
                                <td><span class="badge badge-orange text-white" style="background: orange;">{{ $log->status }}</span></td>
                                <td><span class="badge badge-info">{{ $log->total_amount ?? 0 }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>