<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SafeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id' ,
        'safe_id',
        'opening_balance',
        'income',
        'outcome',
        'balance',
    ];
}
