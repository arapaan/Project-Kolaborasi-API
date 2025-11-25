<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;

use App\Models\Notification;
use Illuminate\Http\Request;
use function Symfony\Component\Clock\now;

class OrderController extends Controller
{    
    public function __invoke(Request $request)
    {
        \Log::info("ORDER REQUEST", $request->all());
        $request->validate([
            'user_id'       => ['required'],
            'status'        => ['required'],
            'total_price'   => ['required', 'numeric'],
            'details'       => ['required'],       // custom order details
        ]);

        $order = Order::create([
            'business_id'   => $request->business_id,
            'user_id'       => $request->user_id,  
            'created_by'    => $request->created_by,
            'status'        => 'pending', // override agar aman
            'total_price'   => $request->total_price, 
            'details'       => json_encode($request->details), // json
        ]);

        $productsData = [];
        foreach ($request->products as $p) {
            $productsData[$p['product_id']] = [
                'quantity'   => $p['quantity'] ?? 1,
                'final_price' => $p['final_price'],
                'variant_name' => $p['variant_name'],
            ];
        }

        $order->products()->attach($productsData);

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

    public function createSnapToken(Request $request)
    {
        \Log::info("Create Snap Token", $request->all());

        $order = Order::find($request->order_id);
        if (!$order) return response()->json([
            'error' => 'Order Not Found'
        ], 404);

        $item = [];
         foreach ($order->products as $p) {

            // FINAL PRICE SEHARUSNYA ADA DI PIVOT
            $finalPrice = $p->pivot->final_price ?? $p->price;

            $items[] = [
                'id'        => $p->id,
                'price'     => $finalPrice,
                'quantity'  => $p->pivot->quantity,
                'name'      => $p->name . ($p->pivot->variant_name ? " ({$p->pivot->variant_name})" : ""),
            ];
        }            

         $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => intval($request->total_price),
            ],
            'item_details' => [
                [
                    'id'        => 'ORDER-' . $order->id,
                    'price'     => intval($request->total_price), // harga final 29.000
                    'quantity'  => 1,
                    'name'      => 'Pembayaran Pesanan #' . $order->id,
                ]
            ],
            'customer_details' => $request->user ?? [],
        ];

        \Log::info("MIDTRANS PARAMS", $params);

        $snapToken = \Midtrans\Snap::getSnapToken($params);        

        return response()->json([
            'token' => $snapToken,
        ]);
    }

    public function handleNotification(Request $request)
    {
        \Log::info("MIDTRANS NOTIFICATION", $request->all());

        $json = json_decode($request->getContent(), true);
        if (!$json) $json = $request->all();
    
        $notif = new \Midtrans\Notification();
    
        $transaction = $notif->transaction_status;
        $orderId     = $notif->order_id;
        $fraud       = $notif->fraud_status;
    
        // Cari order berdasarkan ID
        $order = Order::with('products')->find($orderId);
    
        if (!$order) {
            \Log::error("ORDER TIDAK DITEMUKAN UNTUK ID: $orderId");
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        // Tentukan status
        if ($transaction == 'capture') {
            $order->status = ($fraud == 'challenge') ? 'challenge' : 'success';
        }
        else if ($transaction == 'settlement') {
            $order->status = 'success';
        }
        else if ($transaction == 'pending') {
            $order->status = 'pending';
        }
        else if ($transaction == 'deny') {
            $order->status = 'deny';
        }
        else if ($transaction == 'expire') {
            $order->status = 'expired';
        }
        else if ($transaction == 'cancel') {
            $order->status = 'cancel';
        }
    
        $order->save();
    
        // KURANGIN STOK PRODUK
        if ($order->status == 'success') {
            foreach ($order->products as $p) {
                $product = Product::find($p->id);
                if ($product) {
                    $product->quantity -= $p->pivot->quantity;
                    $product->save();
                }
            }
        }
    
        // Simpan ke tabel notifications (jika lo punya)
        Notification::create([
            'user_id' => $order->user_id,
            'title'   => "Pembayaran {$order->status}",
            'content' => "Order #$orderId sekarang berstatus {$order->status}",
        ]);
    
        return response()->json(['message' => 'OK']);
    }

    public function userOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                ->with(['products' => function($query) {
                    $query->withPivot(['quantity', 'final_price', 'variant_name']);
                }])
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
    
        $order->status = $request->status;
        $order->save();
    
        return response()->json([
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }
}
