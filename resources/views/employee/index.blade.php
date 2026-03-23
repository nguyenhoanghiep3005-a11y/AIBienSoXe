@extends('layouts.admin')
<style>
    /* Ép màu cho chữ trong Tab hiện lên */
    .nav-tabs .nav-link {
        color: #333 !important; /* Màu đen xám dễ nhìn */
        font-weight: bold;
        padding: 10px 20px;
        background-color: #f8f9fa; /* Nền xám nhạt cho tab chưa chọn */
        border: 1px solid #dee2e6;
    }
    
    /* Màu khi Tab được chọn (Active) */
    .nav-tabs .nav-link.active {
        color: #007bff !important; /* Màu xanh đậm */
        background-color: #fff !important;
        border-bottom: 2px solid #007bff !important;
    }
</style>
@section('content')
<div class="card shadow border-0">
    <div class="card-body">
        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="nhanvien-tab" data-toggle="tab" href="#nhanvien" role="tab">Nhân Viên</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="taikhoan-tab" data-toggle="tab" href="#taikhoan" role="tab">Tài Khoản</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="nhanvien" role="tabpanel">
                <form action="#" method="POST" class="mb-5">
                    <div class="form-group">
                        <label>Tên nhân viên</label>
                        <input type="text" class="form-control" placeholder="Tên nhân viên">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Giới tính</label>
                        <select class="form-control">
                            <option>Nam</option>
                            <option>Nữ</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control" placeholder="Địa chỉ">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control" placeholder="Số điện thoại">
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm">Thêm nhân viên</button>
                </form>

                <h5 class="mb-3 font-weight-bold">Danh sách nhân viên</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light text-secondary">
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
                            <tr>
                                <td>admin</td>
                                <td>1982-10-02</td>
                                <td>Nam</td>
                                <td>Hà Nội</td>
                                <td>086832112</td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white"><i class="fas fa-edit"></i> Sửa</button>
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Xóa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="taikhoan" role="tabpanel">
                <form action="#" method="POST" class="mb-5">
                    <div class="form-group">
                        <label>Nhân viên</label>
                        <select class="form-control">
                            <option>--Chọn nhân viên--</option>
                            <option>admin</option>
                            <option>Nguyễn Lâm 99</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tài khoản</label>
                        <input type="text" class="form-control" placeholder="Tài khoản">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="password" class="form-control" placeholder="Mật khẩu">
                    </div>
                    <div class="form-group">
                        <label>Quyền</label>
                        <select class="form-control">
                            <option>--Chọn quyền--</option>
                            <option>Admin</option>
                            <option>Nhân viên</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm">Thêm tài khoản</button>
                </form>

                <h5 class="mb-3 font-weight-bold">Danh sách tài khoản</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th>Tên nhân viên</th>
                                <th>Tên tài khoản</th>
                                <th>Quyền</th>
                                <th>Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>admin</td>
                                <td>admin</td>
                                <td>Admin</td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white"><i class="fas fa-edit"></i> Sửa</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection