<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_type_id', 
        'time_block_name', 
        'start_time', 
        'end_time', 
        'price'
    ];

    /**
     * Cấu hình giá thuộc về một loại xe nhất định
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}