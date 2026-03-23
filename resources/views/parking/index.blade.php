@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">Thông tin nhận dạng biển số xe thủ công</div>
            <div class="card-body">
                <form action="{{ route('parking.recognize') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Chọn hình ảnh biển số:</label>
                        <input type="file" name="plate_image" class="form-control-file border p-2" required>
                    </div>
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-search"></i> Nhận diện biển số
                    </button>
                </form>

                @if(session('plate'))
                <div class="mt-4 p-3 bg-light border rounded text-center">
                    <h5 class="text-danger font-weight-bold">Biển số vừa nhận diện được:</h5>
                    <h2 class="display-4 text-primary font-weight-bold">{{ session('plate') }}</h2>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-warning">Danh sách biển số vừa vào</div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Loại xe</th>
                            <th>Biển số</th>
                            <th>Thời gian vào</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->vehicle_type }}</td>
                            <td class="text-success font-weight-bold">{{ $log->license_plate }}</td>
                            <td>{{ $log->time_in }}</td>
                            <td><span class="badge badge-warning">Đang gửi xe</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection