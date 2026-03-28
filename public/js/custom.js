/**
 * File: public/js/parking.js
 * Chức năng: Xử lý giao diện camera, preview ảnh và gọi API lên Laravel Controller
 */

document.addEventListener('DOMContentLoaded', function() {
    // Lắng nghe sự kiện chọn file ảnh để hiển thị Preview
    const imageIn = document.getElementById('image-in');
    const imageOut = document.getElementById('image-out');

    if (imageIn) {
        imageIn.addEventListener('change', function(e) { showPreview(e, 'preview-in'); });
    }
    if (imageOut) {
        imageOut.addEventListener('change', function(e) { showPreview(e, 'preview-out'); });
    }
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
    
    // Kiểm tra xem đã chọn ảnh chưa
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        alert('Vui lòng chọn hoặc chụp một bức ảnh trước khi quét!');
        return;
    }

    // Đóng gói dữ liệu ảnh vào FormData
    const formData = new FormData();
    formData.append('image', fileInput.files[0]); 

    // Xác định URL (Endpoint) tương ứng với khai báo trong routes/web.php
    const url = type === 'in' ? '/parking/check-in' : '/parking/check-out';
    
    // Lấy CSRF token từ thẻ meta của Laravel (Bắt buộc cho POST request)
    const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMetaTag ? csrfMetaTag.getAttribute('content') : '';

    if (!csrfToken) {
        console.error("Không tìm thấy CSRF Token. Hãy đảm bảo bạn đã thêm thẻ meta csrf-token vào layout.");
    }

    // Lấy button đang được click để tạo hiệu ứng loading
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    
    try {
        // Vô hiệu hóa nút và đổi text thành đang xử lý
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
        btn.disabled = true;

        // Gửi request lên Laravel Controller
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken, // Token bảo mật của Laravel
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await response.json();

        // Xử lý phản hồi từ server
        if (response.ok && data.success) {
            alert(`${data.message}! Biển số: ${data.data.plate || 'Không rõ'}`);
            
            // Reload trang để cập nhật lại bảng lịch sử
            window.location.reload();
        } else {
            alert('Lỗi: ' + (data.message || 'Hệ thống không thể nhận dạng được biển số.'));
            console.error(data.error);
        }

    } catch (error) {
        console.error('Error:', error);
        alert('Lỗi kết nối đến máy chủ. Vui lòng kiểm tra lại server.');
    } finally {
        // Phục hồi lại trạng thái ban đầu của nút bấm
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}