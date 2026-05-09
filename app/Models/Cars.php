<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cars extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'code',
      'car_number',
      'driver_name',
      'phone',
      'notes',
      'user_ins',
      'user_upd'
    ];
}
