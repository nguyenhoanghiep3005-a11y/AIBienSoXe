<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('price_configs', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng vehicle_types
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->onDelete('cascade');
            
            $table->string('time_block_name'); // Tên ca/khung giờ: VD "Ban ngày", "Ban đêm"
            $table->time('start_time'); // Giờ bắt đầu: 06:00:00
            $table->time('end_time');   // Giờ kết thúc: 18:00:00
            $table->decimal('price', 10, 2); // Giá tiền cho khung giờ này
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('price_configs');
    }
};