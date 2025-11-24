<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function handle(Request $request)
    {
        $notification = new Notification();

        $order = Order::find($notification->order_id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $status = $notification->transaction_status;
        $fraud = $notification->fraud_status;

        if ($status === 'capture' && $fraud === 'accept') {
            $order->status = 'paid';
        } elseif ($status === 'settlement') {
            $order->status = 'paid';
        } elseif ($status === 'pending') {
            $order->status = 'pending';
        } elseif (in_array($status, ['deny','expire','cancel'])) {
            $order->status = 'failed';
        }

        $order->save();

        return response()->json(['message' => 'OK']);
    }
}
