<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'created_by',
        'status',
        'total_price',
        'product'
    ];
}
