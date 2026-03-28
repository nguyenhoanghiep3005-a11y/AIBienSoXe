document.addEventListener('DOMContentLoaded', function() {
    // 1. Lắng nghe sự kiện chọn file ảnh để hiển thị Preview
    const imageIn = document.getElementById('image-in');
    const imageOut = document.getElementById('image-out');

    if (imageIn) imageIn.addEventListener('change', function(e) { showPreview(e, 'preview-in'); });
    if (imageOut) imageOut.addEventListener('change', function(e) { showPreview(e, 'preview-out'); });
});

// Hàm hiển thị ảnh xem trước
function showPreview(event, previewId) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById(previewId);
            const img = previewDiv.querySelector('img');
            img.src = e.target.result;
            previewDiv.classList.remove('d-none');
        }
        reader.readAsDataURL(file);
    }
}

// Hàm xử lý khi bấm nút "QUÉT & CHO XE VÀO / RA"
async function handleParking(type) {
    const inputId = type === 'in' ? 'image-in' : 'image-out';
    const fileInput = document.getElementById(inputId);
    
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        alert('Vui lòng chọn hoặc chụp một bức ảnh trước khi quét!');
        return;
    }

    const formData = new FormData();
    formData.append('image', fileInput.files[0]); 

    // Gọi lên Laravel Controller của bạn
    const url = type === 'in' ? '/parking/check-in' : '/parking/check-out';
    
    // Đừng quên thêm <meta name="csrf-token" content="{{ csrf_token() }}"> vào thẻ <head> trong resources/views/admin.blade.php
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    
    try {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
        btn.disabled = true;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            alert(`${data.message}! Biển số: ${data.data.plate || 'Không rõ'}`);
            
            // Gọi hàm DOM manipulation đổ dữ liệu từ Template
            updateHistoryTable(data.data, type);
        } else {
            alert('Lỗi: ' + (data.message || 'Không nhận diện được biển số.'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi kết nối đến máy chủ. Vui lòng kiểm tra lại server.');
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

// Hàm cập nhật bảng bằng cách Clone <template>
function updateHistoryTable(vehicleData, actionType) {
    const tbody = document.querySelector('#parking-history-table tbody');
    const template = document.getElementById('history-row-template');

    if (!tbody || !template) return;

    // 1. Chuẩn bị dữ liệu text
    const now = new Date();
    const timeString = now.toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });

    const plate = vehicleData.plate || 'Không rõ';
    const typeStr = vehicleData.type || (actionType === 'in' ? 'Đang xác định' : 'Không rõ');
    const timeIn = actionType === 'in' ? timeString : (vehicleData.time_in || 'Trước đó');
    const timeOut = actionType === 'out' ? timeString : '-';
    
    const statusHtml = actionType === 'in' 
        ? '<span class="badge bg-primary">Đang đỗ</span>'
        : '<span class="badge bg-secondary">Đã ra</span>';

    const feeStr = vehicleData.fee ? vehicleData.fee.toLocaleString() + ' đ' : 'Đã tính';
    const feeHtml = actionType === 'out' ? `<span class="text-danger font-weight-bold">${feeStr}</span>` : '-';

    // 2. Clone template và bơm dữ liệu
    const clone = template.content.cloneNode(true);
    const tr = clone.querySelector('tr');
    
    // Hiệu ứng nháy màu xanh 2.5 giây như trong hình của bạn
    setTimeout(() => tr.classList.remove('table-success'), 2500);

    // Điền dữ liệu vào các cột dựa trên class
    clone.querySelector('.col-plate').textContent = plate;
    clone.querySelector('.col-type').textContent = typeStr;
    clone.querySelector('.col-time-in').textContent = timeIn;
    clone.querySelector('.col-time-out').textContent = timeOut;
    clone.querySelector('.col-status').innerHTML = statusHtml;
    clone.querySelector('.col-fee').innerHTML = feeHtml;

    // 3. Xóa dòng "Chưa có lịch sử" nếu có, rồi chèn dòng mới lên đầu
    const emptyRow = tbody.querySelector('.text-muted');
    if (emptyRow) emptyRow.remove();
    tbody.prepend(clone);
    
    // 4. Reset giao diện
    const previewId = actionType === 'in' ? 'preview-in' : 'preview-out';
    document.getElementById(previewId)?.classList.add('d-none');
    const inputId = actionType === 'in' ? 'image-in' : 'image-out';
    if (document.getElementById(inputId)) document.getElementById(inputId).value = '';
}