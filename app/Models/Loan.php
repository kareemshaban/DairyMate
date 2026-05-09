<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'date',
        'bill_number',
        'supplier_id',
        'safe_id',
        'amount',
        'installment_amount',
        'installment_count',
        'start_date',
        'remaining_installments',
        'paid_installments',
        'notes',
        'user_ins',
        'user_upd',
    ];
}
