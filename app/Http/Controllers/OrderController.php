<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'business_id'   => ['required', 'integer'],
            'user_id'       => ['required', 'integer'],
            'status'        => ['required', 'string'],
            'total_price'   => ['required', 'numeric'],
            'product'       => ['required', 'array'],
        ]);

        // 1. SIMPAN ORDER
        $order = Order::create([
            'business_id'   => $request->business_id,
            'user_id'       => $request->user_id,
            'status'        => $request->status,
            'total_price'   => $request->total_price,
        ]);

        // 2. SIMPAN PRODUK KE PIVOT (order_product)
        foreach ($request->product as $item) {
            $order->products()->attach($item['id'], [
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Order successfully added',
            'data'    => $order->load('products'),
        ], 201);
    }


    public function salesPerMonth()
    {
        $sales = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formatted = array_fill(1, 12, 0);
        foreach ($sales as $s) {
            $formatted[$s->month] = (float) $s->total;
        }

        return response()->json(array_values($formatted));
    }

    public function status($orderId)
    {
        $order = Order::where('order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Map status string to step number
        $stepMap = [
            'placed' => 0,
            'processing' => 1,
            'delivery' => 2,
            'delivered' => 3,
        ];

        return response()->json([
            'id' => $order->order_id,
            'items' => json_decode($order->items, true),
            'itemCount' => $order->item_count,
            'eta' => $order->eta,
            'currentStep' => $stepMap[$order->status] ?? 0,
        ]);
    }
}
