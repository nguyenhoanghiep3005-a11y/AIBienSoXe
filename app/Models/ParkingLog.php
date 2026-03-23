<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'location_id', 'license_plate', 'car_name',
        'time_in', 'time_out', 'image_path', 'status'
    ];

    // Mối quan hệ: Một lịch sử thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mối quan hệ: Một lịch sử thuộc về một vị trí
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}