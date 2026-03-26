<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hệ Thống Nhận Diện Biển Số</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-8 border-b border-gray-200 bg-blue-600">
                <h1 class="text-3xl font-bold text-center text-white">🚗 AI Nhận Diện Biển Số Xe</h1>
                <p class="text-center text-blue-100 mt-2">Mô phỏng bãi giữ xe thông minh</p>
            </div>

            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn ảnh biển số xe cần kiểm tra:</label>
                    <input type="file" id="imageInput" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150 ease-in-out">
                </div>

                <div class="mb-6 flex justify-center">
                    <img id="imagePreview" src="" alt="Ảnh xem trước" class="hidden max-h-64 rounded-lg shadow-md border border-gray-200">
                </div>

                <div class="flex justify-center gap-4 mb-8">
                    <button onclick="processAction('in')" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded shadow-md transition duration-200">
                        ⬇️ Xe Vào (Check-in)
                    </button>
                    <button onclick="processAction('out')" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow-md transition duration-200">
                        ⬆️ Xe Ra (Check-out)
                    </button>
                </div>

                <div id="resultArea" class="hidden bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Kết Quả Phân Tích</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><span class="font-semibold text-gray-600">Trạng thái:</span> <span id="resStatus" class="font-bold"></span></div>
                        <div><span class="font-semibold text-gray-600">Hành động:</span> <span id="resAction"></span></div>
                        <div><span class="font-semibold text-gray-600">Biển số nhận diện:</span> <span id="resPlate" class="text-blue-600 font-bold text-xl"></span></div>
                        <div><span class="font-semibold text-gray-600">Thời gian:</span> <span id="resTime"></span></div>
                        <div class="col-span-2"><span class="font-semibold text-gray-600">Thông báo:</span> <span id="resMessage" class="italic text-gray-700"></span></div>
                    </div>
                </div>
                
                <div id="loading" class="hidden text-center text-blue-600 font-semibold my-4">
                    Đang xử lý ảnh, vui lòng đợi...
                </div>
            </div>
        </div>
    </div>

    <script>
        // Xử lý hiển thị ảnh xem trước
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Hàm gửi API
        async function processAction(actionType) {
            const file = imageInput.files[0];
            if (!file) {
                alert('Vui lòng chọn ảnh trước khi thực hiện!');
                return;
            }

            // Giao diện
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('resultArea').classList.add('hidden');

            const formData = new FormData();
            formData.append('image', file);

            const url = actionType === 'in' ? '/api/proxy/check-in' : '/api/proxy/check-out';
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();
                
                // Hiển thị kết quả
                document.getElementById('loading').classList.add('hidden');
                document.getElementById('resultArea').classList.remove('hidden');
                
                const statusEl = document.getElementById('resStatus');
                if (data.success) {
                    statusEl.textContent = 'Thành công';
                    statusEl.className = 'font-bold text-green-600';
                    document.getElementById('resPlate').textContent = data.plate_number;
                    document.getElementById('resAction').textContent = data.action === 'CHECK_IN' ? 'Vào bãi' : 'Ra bãi';
                    document.getElementById('resTime').textContent = data.time;
                    document.getElementById('resMessage').textContent = data.message;
                } else {
                    statusEl.textContent = 'Thất bại';
                    statusEl.className = 'font-bold text-red-600';
                    document.getElementById('resPlate').textContent = 'Không xác định';
                    document.getElementById('resAction').textContent = '-';
                    document.getElementById('resTime').textContent = '-';
                    document.getElementById('resMessage').textContent = data.message || 'Lỗi không xác định';
                }

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('loading').classList.add('hidden');
                alert('Đã xảy ra lỗi khi kết nối tới server!');
            }
        }
    </script>
</body>
</html>