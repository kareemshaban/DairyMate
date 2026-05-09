<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheeseMeal extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'code',
      'symbol',
      'date',
      'cheese_price',
      'white_cheese_price',
      'cream_price',
      'protein_price',
      'milk_weight',
      'bovine_price',
      'type',
      'item_id',
      'quantity',
      'weight',
      'disk_weight',
      'disk_cost',
      'productivity',
      'cost_per_cheese_kilo',
      'cream_weight',
      'cream_of_kilo_milk',
      'protein_weight',
      'protein_of_kilo_milk',
      'net',
      'notes',
      'state', // 0 new 1 posted
      'user_ins',
      'user_upd'
    ];
}
