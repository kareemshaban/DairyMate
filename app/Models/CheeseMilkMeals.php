<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheeseMilkMeals extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'daily_milk_meal_id',
      'cheese_meal_id',
      'user_ins',
      'user_upd'
    ];
}
