<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    /**
     * Một loại xe có thể có nhiều cấu hình giá (theo khung giờ)
     */
    public function priceConfigs()
    {
        return $this->hasMany(PriceConfig::class);
    }

    /**
     * Một loại xe có thể có nhiều lượt ra vào
     */
    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}
