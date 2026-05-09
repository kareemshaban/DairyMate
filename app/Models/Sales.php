<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'bill_number',
      'date',
      'client_id',
      'store_id',
      'total',
      'discount',
      'net',
      'notes',
      'state',
      'user_ins',
      'user_upd'
    ];
}
