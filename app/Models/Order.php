<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'business_id',
        'user_id',
        'created_by',
        'status',
        'total_price',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function business()
    {
        return  $this->belongsTo(Business::class, 'business_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                ->withPivot(['quantity'])
                ->withTimestamps();
    }

    public function staff()
   {
    return $this->belongsTo(User::class, 'staff_id');
   }


    protected static function booted()
    {
    static::created(function ($order) {
        foreach ($order->products as $product) {
            $product->decrement('quantity', $product->pivot->quantity);
        }
    });

    static::deleted(function ($order) {
        foreach ($order->products as $product) {
            $product->increment('quantity', $product->pivot->quantity);
        }
    });
    
}
}
