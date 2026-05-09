<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDailyMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'date',
        'type', // 0 morning 1 evening
        'weakly_meal_id',
        'supplier_id', // the main supplier (car owner)
        'member_id',
        'weight',
        'price',
        'weight_b',
        'price_b',
        'total',
        'state',
        'isPaid',
        'paid',
        'user_ins',
        'user_upd'
    ];
}
