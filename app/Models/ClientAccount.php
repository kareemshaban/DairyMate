<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAccount extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'client_id',
      'debit', // عليه فلوس
      'credit' , // له فلوس
      'opening_balance_debit',
      'opening_balance_credit',
        'balance',
      'user_ins',
      'user_upd'
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id');
    }
}
