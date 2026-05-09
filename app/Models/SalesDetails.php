<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetails extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'bill_id',
      'date',
      'item_id',
      'meal_id',
      'store_id',
      'quantity',
      'weight',
      'price',
      'total',
      'user_ins',
      'user_upd'
    ];
}
