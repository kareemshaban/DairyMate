<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   StoreQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'store_id',
      'item_id',
      'cheese_meal_id',
      'opening_quantity',
      'quantity_in',
      'quantity_out',
      'balance' ,
      'user_ins',
      'user_upd',
      'available_quantity'
    ];
}
