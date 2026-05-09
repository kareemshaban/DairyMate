<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'date',
        'car_id',
        'tota_weight',
        'weight_difference',
        'state', // 0 new , 1 posted
        'weakly_meal_id',
        'notes',
        'user_ins',
        'user_upd'
    ];
}
