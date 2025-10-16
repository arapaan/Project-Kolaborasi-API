<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'business_id'   => ['required'],
            'user_id'       => ['required'],
            'status'        => ['required'],
            'total_price'   => ['required'],
            'product'       => ['required']
        ]);

        $order = Order::create([
            'business_id'   => $request->business_id,
            'user_id'       => $request->user_id,  
            'created_by'    => $request->created_by,
            'status'        => $request->status,
            'total_price'   => $request->total_price,
            'product'       => $request->product
        ]);

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Order successfully added',
            'data'      => $order
        ]);
    }

    public function salesPerMonth()
    {
        $sales = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formatted = array_fill(1, 12, 0);
        foreach ($sales as $s)
            {
                $formatted[$s->month] = (float) $s->total;
            }
        
        return response()->json(array_values($formatted));
    }
}
