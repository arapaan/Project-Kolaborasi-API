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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
