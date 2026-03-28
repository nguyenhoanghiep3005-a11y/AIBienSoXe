<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parking_logs', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->index(); // Đánh index biển số để truy vấn nhanh hơn
            
            // Khóa ngoại liên kết bảng vehicle_types, nullable để dự phòng trường hợp AI không nhận diện được loại xe ngay lập tức
            $table->foreignId('vehicle_type_id')->nullable()->constrained('vehicle_types')->onDelete('set null');
            
            // Thông tin lúc xe vào
            $table->dateTime('time_in');
            $table->string('image_in')->nullable(); // Đường dẫn ảnh xe lúc vào
            
            // Thông tin lúc xe ra
            $table->dateTime('time_out')->nullable();
            $table->string('image_out')->nullable(); // Đường dẫn ảnh xe lúc ra
            
            // Trạng thái: Đang gửi (parking) hoặc Đã trả (completed)
            $table->enum('status', ['parking', 'completed'])->default('parking');
            
            // Tổng tiền thu được
            $table->decimal('total_price', 10, 2)->default(0); 
            $table->text('note')->nullable(); // Ghi chú thêm
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parking_logs');
    }
};