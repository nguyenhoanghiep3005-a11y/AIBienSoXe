<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingLog extends Model
{
    use HasFactory;

    // Khai báo các cột được phép thêm dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'license_plate', 
        'vehicle_type_id', 
        'time_in', 
        'image_in',
        'time_out', 
        'image_out', 
        'status', 
        'total_price', 
        'note'
    ];

    // Ép kiểu dữ liệu tự động khi lấy từ Database ra
    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /**
     * Mối quan hệ: Lịch sử gửi xe này thuộc về 1 Loại xe cụ thể (Ô tô, Xe máy,...)
     */
    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    /**
     * Accessor: Tự động tạo mã HTML badge trạng thái để dùng trực tiếp ngoài view Blade
     * Cách dùng ngoài view: {!! $log->status_badge !!}
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'parking') {
            return '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Đang gửi xe</span>';
        }
        return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Đã trả xe</span>';
    }
}