<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatchRecipit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'date',
        'bill_number',
        'client_id',
        'amount',
        'payment_method',
        'safe_id',
        'loan_id',
        'notes',
        'state',
        'user_ins',
        'user_upd',
    ];
}
