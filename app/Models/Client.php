<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'sort',
      'type',
      'name',
      'phone',
      'buffalo_min_limit',
      'buffalo_max_limit',
      'bovine_min_limit',
      'bovine_max_limit',
      'address',
      'car_id',
       'car_supplier_id',
        'is_farmer',
        'user_ins',
        'user_upd',
        'buffalo_price',
        'bovine_price',
        
    ];

    public function balance()
    {
        return $this->hasOne(ClientAccount::class, 'client_id');
    }
}
