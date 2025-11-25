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
                ->withPivot(['quantity', 'final_price', 'variant_name'])
                ->withTimestamps();
    }

    public function staff()
   {
    return $this->belongsTo(User::class, 'staff_id');
   }


   protected static function booted()
    {
        static::created(function ($order) {
            \Log::info("Order created - ID: {$order->id}");
            
            foreach ($order->products as $product) {
                \Log::info("Decrementing product: {$product->id}, quantity: {$product->pivot->quantity}");
                $product->decrement('quantity', $product->pivot->quantity);
            }
        });
    }
}
