// 1. Xử lý Ảnh Preview
document.getElementById('inputImage').onchange = evt => {
    const [file] = evt.target.files;
    if (file) {
        document.getElementById('previewImage').src = URL.createObjectURL(file);
        document.getElementById('previewImage').style.display = 'block';
        document.getElementById('placeholder').style.display = 'none';
    }
}

// 2. Xử lý Camera
let stream = null;
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        document.getElementById('videoElement').srcObject = stream;
        document.getElementById('videoElement').style.display = 'block';
        document.getElementById('camPlaceholder').style.display = 'none';
        document.getElementById('btnStartCamera').disabled = true;
        document.getElementById('btnStopCamera').disabled = false;
    } catch (err) { alert("Lỗi Camera!"); }
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        document.getElementById('videoElement').style.display = 'none';
        document.getElementById('camPlaceholder').style.display = 'block';
        document.getElementById('btnStartCamera').disabled = false;
        document.getElementById('btnStopCamera').disabled = true;
    }
}

// 3. Nhận diện & Đổ bảng
async function recognizePlate(url) {
    const fileInput = document.getElementById('inputImage');
    const resultInput = document.getElementById('resultInput');
    if (!fileInput.files[0]) return alert("Chưa chọn ảnh!");

    resultInput.value = "Đang nhận diện...";
    let formData = new FormData();
    formData.append('plate_image', fileInput.files[0]);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        const res = await fetch(url, { method: 'POST', body: formData });
        const data = await res.json();
        resultInput.value = data.plate || "N/A";
    } catch (e) { resultInput.value = "Lỗi kết nối"; }
}

function addPlateToTable() {
    const plate = document.getElementById('resultInput').value;
    const type = document.getElementById('vehicleType').value.split(' - ')[0];
    if (!plate || plate.includes("...")) return;

    const table = document.querySelector('#parkingTable tbody');
    const row = document.importNode(document.getElementById('rowTemplate').content, true);
    const now = new Date();

    row.querySelector('.v-type').textContent = type;
    row.querySelector('.v-plate').textContent = plate;
    row.querySelector('.v-time').innerHTML = `${now.toLocaleDateString()}<br><span class="text-primary">${now.toLocaleTimeString()}</span>`;
    
    table.prepend(row);
    // Reset Form
    document.getElementById('resultInput').value = "";
    document.getElementById('previewImage').style.display = 'none';
    document.getElementById('placeholder').style.display = 'block';
}