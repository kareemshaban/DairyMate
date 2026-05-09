<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransactionIn extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'bill_number',
        'meal_id',
        'date',
        'store_id',
        'notes',
        'user_ins',
        'user_upd'
    ];
}
