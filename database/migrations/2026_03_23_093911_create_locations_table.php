<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên vị trí, VD: Khu A, Khu B
            $table->string('floor'); // Tầng, VD: Tầng 1, Tầng 2
            $table->integer('capacity')->default(100); // Sức chứa
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
