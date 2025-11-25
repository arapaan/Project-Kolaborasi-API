<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Business;
use App\Models\category;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'business_id',
        'quantity',
        'price',
        'url_png'
    ];

    public function business()
    {
        return  $this->belongsTo(Business::class, 'business_id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }
}
