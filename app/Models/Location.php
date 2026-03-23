<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'floor', 'capacity'];

    // Mối quan hệ: Một vị trí có nhiều xe đỗ
    public function parkingLogs()
    {
        return $this->hasMany(ParkingLog::class);
    }
}