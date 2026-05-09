<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransformDoc extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'bill_number',
      'date',
      'from_store',
      'to_store',
      'notes',
      'state',
      'user_ins',
      'user_upd',
    ];
}
