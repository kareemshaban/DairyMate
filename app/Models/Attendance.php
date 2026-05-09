<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'employee_id',
      'date',
      'morning_present', // 1 present 0 absent
      'evening_present',
      'state', // 0 new , 1 posted
      'user_ins',
      'user_upd',
    ];
}
