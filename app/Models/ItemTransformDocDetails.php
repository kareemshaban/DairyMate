<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTransformDocDetails extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'doc_id',
      'from_item_id',
      'to_item_id',
      'quantity',
      'weight',
      'user_ins',
      'user_upd'
    ];
}
