<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceConfig extends Model
{
    use HasFactory;

    // Khai báo các cột được phép thao tác thêm/sửa hàng loạt (Mass Assignment)
    protected $fillable = [
        'vehicle_type_id', 
        'time_block_name', 
        'start_time', 
        'end_time', 
        'price'
    ];

    /**
     * Mối quan hệ (Relationship): 
     * Cấu hình giá này áp dụng cho 1 Loại xe cụ thể (thuộc về VehicleType)
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}