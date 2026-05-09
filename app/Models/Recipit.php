<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipit extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'date',
      'bill_number',
      'supplier_id',
      'amount',
      'payment_method',
      'safe_id',
      'notes',
      'isPayment',
      'state',
      'user_ins',
      'user_upd',
    ];
}
