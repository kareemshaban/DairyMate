<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
        'details',
        'default_selling_price',
        'type', // 0 item , 1 cream , 2 protein
        'user_ins',
        'user_upd',
        'available_quantity',
        'cheese_meal_id',
        'item_store_id'

        ];
}
