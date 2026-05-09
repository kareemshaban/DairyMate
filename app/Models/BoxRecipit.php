<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoxRecipit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'date',
        'bill_number',
        'recipit_type',
        'amount',
        'safe_id',
        'notes',
        'state',
        'user_ins',
        'user_upd',
    ];
}
