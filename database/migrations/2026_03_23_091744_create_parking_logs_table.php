<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParkingLogsTable extends Migration
{
    public function up()
    {
        Schema::create('parking_logs', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng users (người điều khiển xe)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // Khóa ngoại liên kết với bảng locations (vị trí xe đỗ)
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            $table->string('license_plate'); // Biển số xe
            $table->string('car_name'); // Tên xe, VD: Toyota Camry, Honda Civic
            $table->dateTime('time_in'); // Thời gian vào
            $table->dateTime('time_out')->nullable(); // Thời gian ra (để null khi mới vào)
            $table->string('image_path')->nullable(); // Đường dẫn lưu ảnh biển số
            $table->string('status')->default('Đang gửi'); // Trạng thái: Đang gửi, Đã ra
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parking_logs');
    }
}