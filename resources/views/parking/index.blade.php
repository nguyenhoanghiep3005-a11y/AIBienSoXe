@extends('layouts.admin')

@section('content')
<style>
    /* Tab hiện đại không bị nhảy dòng */
    .nav-tabs { display: flex !important; flex-wrap: nowrap !important; border-bottom: 2px solid #dee2e6; background: #fff; border-radius: 8px 8px 0 0; }
    .nav-tabs .nav-link { font-size: 13px; font-weight: 600; color: #5a5e66 !important; padding: 12px 20px; border: 1px solid #ddd; background: #f8f9fa; }
    .nav-tabs .nav-link.active { color: #fff !important; background: linear-gradient(135deg, #336699, #4a90e2) !important; border: none; }

    /* Khung nhận diện viền đỏ */
    .group-box { border: 1px solid #f5c6cb; border-radius: 8px; padding: 25px 15px 15px 15px; margin-top: 25px; position: relative; background: #fff; }
    .group-title { position: absolute; top: -14px; left: 15px; background: #fff; padding: 0 10px; color: #a94442; font-weight: bold; }

    /* Khung Preview Camera/Ảnh */
    .preview-container { width: 100%; height: 300px; background: #000; border-radius: 10px; overflow: hidden; position: relative; display: flex; align-items: center; justify-content: center; }
    video, img { width: 100%; height: 100%; object-fit: contain; }
    
    /* Biển số khung xanh */
    .plate-badge { background: #28a745; color: #fff; padding: 5px 15px; border-radius: 6px; font-weight: bold; display: inline-block; font-size: 1rem; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
</style>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="bg-white p-3 border rounded shadow-sm">
            <ul class="nav nav-tabs shadow-sm" id="parkingTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#image-tab"><i class="fas fa-image mr-1"></i> Nhận diện bằng hình ảnh</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#camera-tab"><i class="fas fa-video mr-1"></i> Nhận diện Camera</a></li>
            </ul>

            <div class="mt-3">
                <label class="font-weight-bold small">Loại xe & Giá tiền:</label>
                <select id="vehicleType" class="form-control mb-3">
                    <option>Xe máy - Ngày - 5000</option>
                    <option>Ô tô - Ngày - 20000</option>
                </select>

                <div class="group-box shadow-sm">
                    <span class="group-title text-uppercase small">Xử lý nhận diện</span>
                    
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="image-tab">
                            <input type="file" id="inputImage" class="form-control-file border p-1 mb-2 bg-light rounded">
                            <div class="preview-container mb-3">
                                <img id="previewImage" src="" style="display:none;">
                                <div id="placeholder" class="text-white-50 text-center"><i class="fas fa-upload fa-2x"></i><br>Chọn ảnh để xem trước</div>
                            </div>
                            <button type="button" onclick="recognizePlate('{{ route('parking.recognize') }}')" class="btn btn-info btn-block font-weight-bold py-2"><i class="fas fa-search mr-1"></i> NHẬN DIỆN NGAY</button>
                        </div>

                        <div class="tab-pane fade" id="camera-tab">
                            <div class="preview-container mb-3">
                                <video id="videoElement" autoplay playsinline style="display:none;"></video>
                                <div id="camPlaceholder" class="text-white-50 text-center"><i class="fas fa-video-slash fa-2x"></i><br>Camera đang tắt</div>
                            </div>
                            <div class="d-flex mb-2">
                                <button type="button" id="btnStartCamera" onclick="startCamera()" class="btn btn-success flex-fill mr-1 font-weight-bold">BẬT CAM</button>
                                <button type="button" id="btnStopCamera" onclick="stopCamera()" class="btn btn-danger flex-fill ml-1 font-weight-bold" disabled>TẮT CAM</button>
                            </div>
                            <button type="button" onclick="captureAndRecognize()" class="btn btn-primary btn-block font-weight-bold py-2">CHỤP & NHẬN DIỆN</button>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top text-center">
                        <label class="small font-weight-bold">BIỂN SỐ KẾT QUẢ:</label>
                        <input type="text" id="resultInput" class="form-control border-primary text-primary font-weight-bold text-center mb-3" style="background: #f0f7ff; font-size: 1.6rem;"  placeholder="--- ---">
                        <button type="button" onclick="addPlateToTable()" class="btn btn-warning font-weight-bold text-white w-100 py-3 shadow">XÁC NHẬN VÀ LƯU XUỐNG BẢNG</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="bg-white border rounded shadow-sm overflow-hidden">
            <table id="parkingTable" class="table table-bordered mb-0 text-center table-sm table-hover">
                <thead class="bg-light text-uppercase small font-weight-bold">
                    <tr>
                        <th>Loại xe</th><th>Biển số</th><th>Thời gian vào</th><th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>
    </div>
</div>

<template id="rowTemplate">
    <tr class="fade-in-row">
        <td class="v-type" style="vertical-align: middle;"></td>
        <td class="py-2" style="vertical-align: middle;"><div class="plate-badge v-plate"></div></td>
        <td class="v-time" style="vertical-align: middle;"></td>
        <td style="vertical-align: middle;"><span class="badge badge-warning text-white px-2 py-1">ĐANG GỬI</span></td>
    </tr>
</template>
@endsection