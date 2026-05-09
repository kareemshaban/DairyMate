<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'employee_id',
      'week_start',
      'week_end',
      'total_amount',
      'isPaid',
      'safe_id',
      'user_ins',
      'user_upd',
        'created_at'
    ];
}
