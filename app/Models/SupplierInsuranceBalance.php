<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInsuranceBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'supplier_id',
        'date',
        'balance',
        'notes',
        'state',
        'user_ins',
        'user_upd'
    ];
}
