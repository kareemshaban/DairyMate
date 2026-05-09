<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyMilkMeal extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'code',
      'weakly_meal_id',
      'type',
      'date',
      'supplier_id',
      'buffalo_weight',
      'bovine_weight',
      'hasBonus',
      'bonus',
      'total',
      'notes',
      'isManufactured',
      'car_meal_id' ,
      'user_ins',
      'user_upd',
      'buffalo_price',
      'bovine_price',
      'state',  // 0 => new , 1 => posted
      'isPaid', // 0 notPaid , 1 paid
      'paid',
      'cheese_meal_id'

    ];
}
