@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-2">Thông tin thời gian xe</div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 text-center">
                    <thead><tr><th>Thời gian</th><th>TG_BĐ</th><th>TG_KT</th></tr></thead>
                    <tbody>
                        <tr><td>Đêm</td><td>00:00</td><td>04:59</td></tr>
                        <tr><td>Tháng</td><td>01</td><td>30</td></tr>
                        <tr><td>Tối</td><td>17:00</td><td>23:59</td></tr>
                    </tbody>
                </table>
                <div class="p-2"><button class="btn btn-success btn-sm btn-block">Thêm thời gian</button></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-2">Thông tin thời gian và loại xe</div>
            <div class="card-body p-0 text-center" style="font-size: 0.9rem;">
                <table class="table table-bordered mb-0">
                    <thead><tr><th>Loại xe</th><th>Thời gian</th><th>Giá tiền</th><th>Loại vé</th><th>TGBD</th><th>TGKT</th></tr></thead>
                    <tbody>
                        <tr><td>Xe máy điện</td><td>Sáng</td><td>5000</td><td>Lượt</td><td>05:00</td><td>12:59</td></tr>
                        <tr><td>Xe máy</td><td>Đêm</td><td>10000</td><td>Lượt</td><td>00:00</td><td>04:59</td></tr>
                        <tr><td>Ô tô</td><td>Cả ngày</td><td>150000</td><td>Tháng</td><td>01</td><td>30</td></tr>
                    </tbody>
                </table>
                <div class="p-2 text-left"><button class="btn btn-warning btn-sm text-white">Thêm giá xe</button></div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white py-2">Thông tin loại xe</div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 text-center">
                    <thead><tr><th>Tên loại</th></tr></thead>
                    <tbody>
                        <tr><td>Xe máy điện</td></tr>
                        <tr><td>Xe đạp điện</td></tr>
                        <tr><td>Xe máy</td></tr>
                        <tr><td>Ô tô</td></tr>
                    </tbody>
                </table>
                <div class="p-2"><button class="btn btn-info btn-sm btn-block text-white">Thêm loại xe</button></div>
            </div>
        </div>
    </div>
</div>
@endsection