@extends('layouts.admin')

<style>
    .nav-tabs .nav-link {
        color: #555 !important;
        font-weight: bold;
        padding: 12px 25px;
        background-color: #f1f3f5;
        border: 1px solid #dee2e6;
        transition: 0.3s;
    }
    .nav-tabs .nav-link.active {
        color: #fff !important;
        background: linear-gradient(135deg, #336699, #4a90e2) !important;
        border: none !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .form-control:focus { border-color: #336699; box-shadow: 0 0 0 0.2rem rgba(51,102,153,0.1); }
</style>

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 15px;">
    <div class="card-body p-4">
        <ul class="nav nav-tabs mb-4 border-0" id="myTab" role="tablist">
            <li class="nav-item mr-2">
                <a class="nav-link active rounded-pill" id="nhanvien-tab" data-toggle="tab" href="#nhanvien" role="tab">
                    <i class="fas fa-user-tie mr-2"></i>Nhân Viên
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill" id="taikhoan-tab" data-toggle="tab" href="#taikhoan" role="tab">
                    <i class="fas fa-user-lock mr-2"></i>Tài Khoản
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="nhanvien" role="tabpanel">
                <form action="{{ route('employee.store') }}" method="POST" class="bg-light p-4 rounded mb-5 border">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">TÊN NHÂN VIÊN</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="small font-weight-bold">NGÀY SINH</label>
                            <input type="date" name="birthday" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">GIỚI TÍNH</label>
                            <select name="gender" class="form-control">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">SỐ ĐIỆN THOẠI</label>
                            <input type="text" name="phone" class="form-control" placeholder="038..." required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="small font-weight-bold">ĐỊA CHỈ</label>
                            <input type="text" name="address" class="form-control" placeholder="Số nhà, tên đường..." required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> THÊM NHÂN VIÊN MỚI
                    </button>
                </form>

                <h5 class="mb-3 font-weight-bold text-secondary">Danh sách nhân viên</h5>
                <div class="table-responsive">
                    <table class="table table-hover border text-center bg-white">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Tên nhân viên</th>
                                <th>Ngày sinh</th>
                                <th>Giới tính</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                            <tr>
                                <td class="font-weight-bold">{{ $emp->full_name }}</td>
                                <td>{{ $emp->birthday }}</td>
                                <td><span class="badge badge-info">{{ $emp->gender }}</span></td>
                                <td class="small">{{ $emp->address }}</td>
                                <td>{{ $emp->phone }}</td>
                                <td>
                                    <button class="btn btn-outline-info btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
           <div class="tab-pane fade" id="taikhoan" role="tabpanel">
  <form action="{{ route('account.store') }}" method="POST" class="bg-light p-4 rounded mb-5 border">
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label class="small font-weight-bold">CHỌN NHÂN VIÊN</label>
            <select name="employee_id" class="form-control" required>
                <option value="">-- Chọn nhân viên --</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label class="small font-weight-bold">TÊN ĐĂNG NHẬP (EMAIL/USER)</label>
            <input type="text" name="email" class="form-control" placeholder="nguyenhoanghiep3005@gmail.com" required>
        </div>
        <div class="form-group col-md-6">
            <label class="small font-weight-bold">MẬT KHẨU</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label class="small font-weight-bold">QUYỀN HẠN</label>
            <select name="role" class="form-control">
                <option value="user">Nhân viên bãi xe</option>
                <option value="admin">Quản trị viên</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-success font-weight-bold px-4">
        <i class="fas fa-key mr-1"></i> TẠO TÀI KHOẢN
    </button>
    <h5 class="mb-3 font-weight-bold text-secondary">Danh sách tài khoản hệ thống</h5>
<div class="table-responsive">
    <table class="table table-hover border text-center bg-white shadow-sm">
        <thead class="bg-secondary text-white">
            <tr>
                <th>Tên hiển thị</th>
                <th>Email / Username</th>
                <th>Quyền</th>
                <th>Tác vụ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role == 'admin' ? 'badge-danger' : 'badge-primary' }}">
                        {{ $user->role == 'admin' ? 'Quản trị' : 'Nhân viên' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</form>

    
</div>
@endsection