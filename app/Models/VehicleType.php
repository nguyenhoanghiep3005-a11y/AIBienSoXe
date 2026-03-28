<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    // Quan hệ: 1 loại xe có nhiều cấu hình giá
    public function priceConfigs()
    {
        return $this->hasMany(PriceConfig::class);
    }

    // Quan hệ: 1 loại xe có nhiều lượt gửi
    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}