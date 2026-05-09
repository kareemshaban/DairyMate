<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $fillable = [
     'id' ,
      'buffalo_milk_price',
        'bovine_milk_price',
        'morning_bonus_time',
        'evening_bonus_time',
        'bonus_value',
        'protein_price',
        'cream_price',
        'cheese_price',
        'white_cheese_price',
        'user_ins',
        'user_upd',
    ];
}
