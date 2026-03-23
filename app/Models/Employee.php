<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    // Cho phép các cột này được ghi dữ liệu
    protected $fillable = [
        'full_name',
        'birthday',
        'gender',
        'address',
        'phone'
    ];
}