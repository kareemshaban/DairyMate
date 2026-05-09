<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInsuranceItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'insurance_id',
        'item_id',
        'quantity',
        'weight',
        'user_ins',
        'user_upd'
    ];
}
