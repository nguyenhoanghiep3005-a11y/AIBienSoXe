<form action="/recognize" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" required>
    <button type="submit">Gửi sang AI Nhận Diện</button>
</form>

@if(isset($result))
    <div style="margin-top: 20px; background: #e9ecef; padding: 15px;">
        <h4>Kết quả nhận diện:</h4>
        <pre>{{ json_encode($result, JSON_PRETTY_PRINT) }}</pre>
    </div>
@endif