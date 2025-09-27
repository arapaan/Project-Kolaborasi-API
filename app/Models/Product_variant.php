<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_variant extends Model
{
    protected $fillable = [
        'name',
        'product_id',
        'price',
        'business_id',
    ];
}
