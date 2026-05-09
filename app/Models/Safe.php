<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Safe extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'name',
      'code',
      'details',
        'isDefault' ,
      'user_ins',
      'user_upd'
    ];
}
