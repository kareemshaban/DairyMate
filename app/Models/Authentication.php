<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'role_id',
      'form_id',
        'access_level',
        'user_ins',
        'user_upd'
    ];
}
