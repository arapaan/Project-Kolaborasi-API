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

    public function business()
    {
        return  $this->belongsTo(Business::class, 'business_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product');
    }
}
