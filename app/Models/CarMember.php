<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'supplier_id',
        'name',
        'phone',
        'address',
        'sort',
        'user_ins',
        'user_upd'
    ];
}
