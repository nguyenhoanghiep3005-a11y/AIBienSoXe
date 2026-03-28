document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initAppToasts();
    initParkingPanels();
    initHistoryModal();
});

function initThemeToggle() {
    const themeBtn = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    const themeIcon = themeBtn?.querySelector('i');
    const savedTheme = localStorage.getItem('theme') || 'light';

    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(themeIcon, savedTheme);

    if (!themeBtn) {
        return;
    }

    themeBtn.addEventListener('click', () => {
        const nextTheme = htmlElement.getAttribute('data-bs-theme') === 'light' ? 'dark' : 'light';
        htmlElement.setAttribute('data-bs-theme', nextTheme);
        localStorage.setItem('theme', nextTheme);
        updateThemeIcon(themeIcon, nextTheme);
    });
}

function updateThemeIcon(themeIcon, theme) {
    if (!themeIcon) {
        return;
    }

    themeIcon.className = theme === 'dark' ? 'fas fa-sun fs-4 text-warning' : 'fas fa-moon fs-4';
}

function initAppToasts() {
    const flash = window.APP_FLASH || {};

    if (flash.status) {
        showToast(flash.status, 'success');
    }

    if (Array.isArray(flash.errors)) {
        flash.errors.forEach((error) => showToast(error, 'error'));
    }
}

function showToast(message, type = 'success') {
    const container = document.getElementById('app-toast-container');
    if (!container || !message) {
        return;
    }

    const title = type === 'success' ? 'Thành công' : 'Có lỗi xảy ra';

    const toastEl = document.createElement('div');
    toastEl.className = `toast app-toast toast-${type} align-items-center border-0 mb-3`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.innerHTML = `
        <div class="toast-body">
            <div class="d-flex align-items-start gap-3">
                <i class="fas ${type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'} fs-5 mt-1"></i>
                <div>
                    <div class="app-toast-title">${title}</div>
                    <div class="app-toast-message">${escapeHtml(message)}</div>
                </div>
            </div>
        </div>
    `;

    container.appendChild(toastEl);
    const toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: type === 'success' ? 2600 : 4200,
    });
    toast.show();

    toastEl.addEventListener('hidden.bs.toast', () => {
        toastEl.remove();
    });
}

function initParkingPanels() {
    const panels = document.querySelectorAll('.parking-panel');
    if (!panels.length) {
        return;
    }

    panels.forEach((panel) => {
        panel._state = {
            blob: null,
            stream: null,
            manualTime: false,
        };

        const fileInput = panel.querySelector('.parking-file-input');
        const startCameraBtn = panel.querySelector('.start-camera-btn');
        const captureCameraBtn = panel.querySelector('.capture-camera-btn');
        const recognizeBtn = panel.querySelector('.recognize-btn');
        const saveBtn = panel.querySelector('.save-btn');
        const resetBtn = panel.querySelector('.reset-btn');
        const toggleNoteBtn = panel.querySelector('.toggle-note-btn');
        const timeInput = panel.querySelector('.time-input');

        fileInput?.addEventListener('change', () => {
            panel._state.blob = null;
            stopPanelCamera(panel);
            renderSelectedFile(panel, fileInput.files?.[0] || null);
            hideRecognitionResult(panel);
        });

        startCameraBtn?.addEventListener('click', () => toggleCamera(panel));
        captureCameraBtn?.addEventListener('click', () => captureCamera(panel));
        recognizeBtn?.addEventListener('click', () => recognizeParking(panel));
        saveBtn?.addEventListener('click', () => saveParking(panel));
        resetBtn?.addEventListener('click', () => resetPanel(panel));
        toggleNoteBtn?.addEventListener('click', () => {
            panel.querySelector('.note-wrap')?.classList.toggle('d-none');
        });
        timeInput?.addEventListener('input', () => {
            panel._state.manualTime = true;
        });

        startRealtimeClock(panel);
    });
}

function startRealtimeClock(panel) {
    const timeInput = panel.querySelector('.time-input');
    const timeLabel = panel.querySelector('.live-time-label');

    if (!timeInput || !timeLabel) {
        return;
    }

    const syncTime = () => {
        const now = new Date();
        timeLabel.textContent = `Thời gian thực: ${now.toLocaleTimeString('vi-VN')}`;
        if (!panel._state.manualTime) {
            timeInput.value = formatDateTimeLocal(now);
        }
    };

    syncTime();
    setInterval(syncTime, 1000);
}

async function toggleCamera(panel) {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showToast('Trình duyệt hiện tại chưa hỗ trợ mở camera.', 'error');
        return;
    }

    if (panel._state.stream) {
        stopPanelCamera(panel);
        return;
    }

    const startCameraBtn = panel.querySelector('.start-camera-btn');
    const captureCameraBtn = panel.querySelector('.capture-camera-btn');
    const cameraWrap = panel.querySelector('.camera-wrap');
    const video = panel.querySelector('.parking-video');

    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: { ideal: 'environment' } },
            audio: false,
        });

        panel._state.stream = stream;
        video.srcObject = stream;
        cameraWrap.classList.remove('d-none');
        captureCameraBtn.classList.remove('d-none');
        startCameraBtn.innerHTML = '<i class="fas fa-stop me-2"></i>Tắt camera';
        showToast('Camera đã sẵn sàng. Hãy đưa biển số vào giữa khung rồi xác nhận ảnh.', 'success');
    } catch (error) {
        showToast('Không mở được camera. Bạn có thể dùng cách chọn file ảnh.', 'error');
    }
}

function stopPanelCamera(panel) {
    const startCameraBtn = panel.querySelector('.start-camera-btn');
    const captureCameraBtn = panel.querySelector('.capture-camera-btn');
    const cameraWrap = panel.querySelector('.camera-wrap');
    const video = panel.querySelector('.parking-video');

    if (panel._state.stream) {
        panel._state.stream.getTracks().forEach((track) => track.stop());
        panel._state.stream = null;
    }

    if (video) {
        video.srcObject = null;
    }

    cameraWrap?.classList.add('d-none');
    captureCameraBtn?.classList.add('d-none');

    if (startCameraBtn) {
        startCameraBtn.innerHTML = '<i class="fas fa-video me-2"></i>Mở camera';
    }
}

async function captureCamera(panel) {
    const video = panel.querySelector('.parking-video');
    if (!video || !panel._state.stream) {
        return;
    }

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    canvas.toBlob(async (blob) => {
        panel._state.blob = blob;
        renderBlobPreview(panel, blob);
        hideRecognitionResult(panel);
        stopPanelCamera(panel);
        await recognizeParking(panel);
    }, 'image/jpeg', 0.95);
}

function renderSelectedFile(panel, file) {
    const previewWrap = panel.querySelector('.preview-wrap');
    const previewImage = panel.querySelector('.preview-image');

    if (!file) {
        previewWrap?.classList.add('d-none');
        if (previewImage) {
            previewImage.src = '';
        }
        return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
        previewImage.src = event.target.result;
        previewWrap.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}

function renderBlobPreview(panel, blob) {
    const previewWrap = panel.querySelector('.preview-wrap');
    const previewImage = panel.querySelector('.preview-image');
    const fileInput = panel.querySelector('.parking-file-input');

    if (fileInput) {
        fileInput.value = '';
    }

    previewImage.src = URL.createObjectURL(blob);
    previewWrap.classList.remove('d-none');
}

async function recognizeParking(panel) {
    const image = getPanelImage(panel);
    if (!image) {
        showToast('Vui lòng chọn file ảnh hoặc chụp ảnh từ camera trước.', 'error');
        return;
    }

    const button = panel.querySelector('.recognize-btn');
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang nhận diện...';

    try {
        const formData = new FormData();
        formData.append('image', image, image.name || 'camera-capture.jpg');
        formData.append('action', panel.dataset.type);

        const response = await fetch('/parking/recognize', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Không nhận diện được biển số.');
        }

        fillRecognitionResult(panel, result.data);
        showToast(result.message || 'Đã nhận diện biển số.', 'success');
    } catch (error) {
        showToast(error.message || 'Không nhận diện được biển số.', 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHtml;
    }
}

function fillRecognitionResult(panel, data) {
    const resultBox = panel.querySelector('.recognition-result');
    const plateInput = panel.querySelector('.recognized-plate-input');
    const recognizedSourceInput = panel.querySelector('.recognized-source-input');
    const matchedLogIdInput = panel.querySelector('.matched-log-id-input');
    const matchedLogBox = panel.querySelector('.matched-log-box');
    const noteInput = panel.querySelector('.note-input');

    plateInput.value = data.recognized_plate || '';
    if (recognizedSourceInput) {
        recognizedSourceInput.value = data.recognized_plate || '';
    }
    if (matchedLogIdInput) {
        matchedLogIdInput.value = data.matched_log?.id || '';
    }
    if (noteInput && data.matched_log?.note) {
        noteInput.value = data.matched_log.note;
    }

    if (panel.dataset.type === 'out' && matchedLogBox) {
        matchedLogBox.classList.remove('d-none');
        if (data.matched_log) {
            matchedLogBox.innerHTML = `
                <div class="fw-semibold mb-1">Xe đang gửi trong bãi</div>
                <div class="small text-muted">Biển số hiện tại: ${escapeHtml(data.matched_log.plate || '')}</div>
                <div class="small text-muted">Giờ vào: ${escapeHtml(data.matched_log.time_in || '-')}</div>
                <div class="small text-muted">Loại xe: ${escapeHtml(data.matched_log.vehicle_type || 'Chưa chọn')}</div>
            `;
        } else {
            matchedLogBox.innerHTML = '<div class="text-danger fw-semibold">Chưa tìm thấy lượt xe đang gửi phù hợp. Bạn vẫn có thể sửa biển số rồi lưu thủ công.</div>';
        }
    }

    resultBox.classList.remove('d-none');
}

async function saveParking(panel) {
    const image = getPanelImage(panel);
    const plateNumber = panel.querySelector('.recognized-plate-input')?.value.trim();

    if (!image) {
        showToast('Thiếu ảnh để lưu.', 'error');
        return;
    }

    if (!plateNumber) {
        showToast('Vui lòng nhập biển số trước khi lưu.', 'error');
        return;
    }

    if (panel.dataset.type === 'in') {
        const vehicleTypeId = panel.querySelector('.vehicle-type-select')?.value;
        if (!vehicleTypeId) {
            showToast('Vui lòng chọn loại xe trước khi lưu xe vào.', 'error');
            return;
        }
    }

    const button = panel.querySelector('.save-btn');
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';

    try {
        const formData = new FormData();
        formData.append('image', image, image.name || 'camera-capture.jpg');
        formData.append('plate_number', plateNumber);

        const note = panel.querySelector('.note-input')?.value.trim();
        if (note) {
            formData.append('note', note);
        }

        if (panel.dataset.type === 'in') {
            formData.append('vehicle_type_id', panel.querySelector('.vehicle-type-select')?.value || '');
            const timeIn = panel.querySelector('.time-input')?.value;
            if (timeIn) {
                formData.append('time_in', timeIn);
            }
        } else {
            const recognizedSource = panel.querySelector('.recognized-source-input')?.value;
            const matchedLogId = panel.querySelector('.matched-log-id-input')?.value;
            const timeOut = panel.querySelector('.time-input')?.value;

            if (recognizedSource) {
                formData.append('recognized_plate', recognizedSource);
            }
            if (matchedLogId) {
                formData.append('parking_log_id', matchedLogId);
            }
            if (timeOut) {
                formData.append('time_out', timeOut);
            }
        }

        const response = await fetch(panel.dataset.saveUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            },
            body: formData,
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Không lưu được dữ liệu.');
        }

        prependHistoryRow(result.data);
        resetPanel(panel);
        showToast(result.message || 'Đã lưu thành công.', 'success');
    } catch (error) {
        showToast(error.message || 'Không lưu được dữ liệu.', 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHtml;
    }
}

function resetPanel(panel) {
    stopPanelCamera(panel);
    panel._state.blob = null;
    panel._state.manualTime = false;

    const fileInput = panel.querySelector('.parking-file-input');
    const previewWrap = panel.querySelector('.preview-wrap');
    const previewImage = panel.querySelector('.preview-image');
    const resultBox = panel.querySelector('.recognition-result');
    const matchedLogBox = panel.querySelector('.matched-log-box');
    const noteWrap = panel.querySelector('.note-wrap');

    if (fileInput) {
        fileInput.value = '';
    }
    if (previewWrap) {
        previewWrap.classList.add('d-none');
    }
    if (previewImage) {
        previewImage.src = '';
    }
    if (resultBox) {
        resultBox.classList.add('d-none');
    }
    if (matchedLogBox) {
        matchedLogBox.classList.add('d-none');
        matchedLogBox.innerHTML = '';
    }
    if (noteWrap) {
        noteWrap.classList.add('d-none');
    }

    panel.querySelectorAll('input, textarea, select').forEach((field) => {
        if (field.classList.contains('parking-file-input')) {
            return;
        }
        field.value = '';
    });
}

function hideRecognitionResult(panel) {
    panel.querySelector('.recognition-result')?.classList.add('d-none');
    panel.querySelector('.matched-log-box')?.classList.add('d-none');
}

function getPanelImage(panel) {
    return panel._state.blob || panel.querySelector('.parking-file-input')?.files?.[0] || null;
}

function prependHistoryRow(data) {
    const tbody = document.querySelector('#parking-history-table tbody');
    const template = document.getElementById('history-row-template');
    if (!tbody || !template) {
        return;
    }

    const emptyRow = tbody.querySelector('.empty-row');
    if (emptyRow) {
        emptyRow.remove();
    }

    const clone = template.content.cloneNode(true);
    const row = clone.querySelector('tr');

    clone.querySelector('.col-plate').textContent = data.plate || '-';
    clone.querySelector('.col-type').textContent = data.vehicle_type || 'Chưa chọn';
    clone.querySelector('.col-time-in').textContent = data.time_in || '-';
    clone.querySelector('.col-time-out').textContent = data.time_out || '-';
    clone.querySelector('.col-status').innerHTML = `<span class="badge ${data.status === 'parking' ? 'text-bg-primary' : 'text-bg-secondary'}">${data.status === 'parking' ? 'Đang gửi' : 'Đã ra'}</span>`;
    clone.querySelector('.col-fee').textContent = data.fee ? `${Number(data.fee).toLocaleString('vi-VN')} đ` : '-';
    clone.querySelector('.col-note').textContent = data.note ? shortenText(data.note, 35) : '-';
    clone.querySelector('.col-actions').appendChild(buildHistoryActionButton(data));

    tbody.prepend(clone);
    setTimeout(() => row.classList.remove('table-success'), 2500);
}

function buildHistoryActionButton(data) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-sm btn-outline-dark history-view-btn';
    button.setAttribute('data-bs-toggle', 'modal');
    button.setAttribute('data-bs-target', '#historyDetailModal');
    button.dataset.plate = data.plate || '';
    button.dataset.vehicleType = data.vehicle_type || 'Chưa chọn';
    button.dataset.timeIn = data.time_in || '-';
    button.dataset.timeOut = data.time_out || '-';
    button.dataset.status = data.status === 'parking' ? 'Đang gửi' : 'Đã ra';
    button.dataset.fee = data.fee ? `${Number(data.fee).toLocaleString('vi-VN')} đ` : '-';
    button.dataset.note = data.note || '';
    button.dataset.imageIn = data.image_in_url || '';
    button.dataset.imageOut = data.image_out_url || '';
    button.innerHTML = '<i class="fas fa-eye me-1"></i>Xem';
    return button;
}

function initHistoryModal() {
    const modal = document.getElementById('historyDetailModal');
    if (!modal) {
        return;
    }

    modal.addEventListener('show.bs.modal', (event) => {
        const button = event.relatedTarget;
        if (!button) {
            return;
        }

        document.getElementById('detail-plate').textContent = button.dataset.plate || '-';
        document.getElementById('detail-vehicle-type').textContent = button.dataset.vehicleType || '-';
        document.getElementById('detail-time-in').textContent = button.dataset.timeIn || '-';
        document.getElementById('detail-time-out').textContent = button.dataset.timeOut || '-';
        document.getElementById('detail-status').textContent = button.dataset.status || '-';
        document.getElementById('detail-fee').textContent = button.dataset.fee || '-';
        document.getElementById('detail-note').textContent = button.dataset.note || 'Không có ghi chú.';
        renderDetailImage('detail-image-in', 'detail-image-in-empty', button.dataset.imageIn);
        renderDetailImage('detail-image-out', 'detail-image-out-empty', button.dataset.imageOut);
    });
}

function renderDetailImage(imageId, emptyId, src) {
    const image = document.getElementById(imageId);
    const empty = document.getElementById(emptyId);
    if (src) {
        image.src = src;
        image.classList.remove('d-none');
        empty.classList.add('d-none');
    } else {
        image.src = '';
        image.classList.add('d-none');
        empty.classList.remove('d-none');
    }
}

function shortenText(text, maxLength) {
    return text.length > maxLength ? `${text.slice(0, maxLength)}...` : text;
}

function escapeHtml(text) {
    return String(text)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function formatDateTimeLocal(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}
