<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderStatusController extends Controller
{
   public function show($id)
{
    $order = Order::with('products')->findOrFail($id);

    // Mapping status untuk dikirim ke front end
    $statusNames = [
        'order'     => 'Order',
        'diproses'  => 'Diproses',
        'diantar'   => 'Diantar',
        'selesai'   => 'Selesai',
    ];

    return response()->json([
        'id' => $order->id,
        'status_code' => $order->status,
        'status_name' => $statusNames[$order->status] ?? 'Unknown',
        'items' => $order->products,
    ]);
}


    
}
