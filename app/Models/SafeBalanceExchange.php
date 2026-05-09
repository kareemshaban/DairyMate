<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeBalanceExchange extends Model
{
    use HasFactory;

    protected $fillable = [
       'id',
       'date',
       'bill_number',
       'from_safe_id',
       'to_safe_id',
       'balance',
       'notes',
       'user_ins',
       'user_upd'
    ];
}
