<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransactionDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'transaction_id',
        'meal_id',
        'store_id',
        'item_id',
        'quantity',
        'weight',
        'user_ins',
        'user_upd',
        'available_quantity'

        ];
}
