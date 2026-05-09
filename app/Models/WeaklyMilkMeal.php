<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaklyMilkMeal extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'start_date',
      'end_date',
      'code',
      'state',
      'price_buffalo',
      'price_bovine',
      'total_buffalo_weight',
      'total_bovine_weight',
      'total_money',
      'number_of_daily_meals',
      'notes',
      'post_date',
      'user_ins',
      'user_upd',
      'isManufactured'
    ];
}
