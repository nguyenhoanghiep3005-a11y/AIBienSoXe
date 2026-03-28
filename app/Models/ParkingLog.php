<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_plate',
        'plate_number',
        'vehicle_type_id',
        'time_in',
        'image_in',
        'time_out',
        'image_out',
        'status',
        'fee',
        'total_price',
        'note',
    ];

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    protected function plateNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['license_plate'] ?? null,
            set: fn ($value) => ['license_plate' => strtoupper(trim((string) $value))]
        );
    }

    protected function fee(): Attribute
    {
        return Attribute::make(
            get: fn () => (float) ($this->attributes['total_price'] ?? 0),
            set: fn ($value) => ['total_price' => $value]
        );
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }
}
