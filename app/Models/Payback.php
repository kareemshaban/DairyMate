<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payback extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'safe_id',
      'amount',

    ];
}
