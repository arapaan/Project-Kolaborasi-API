<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id'       => ['required'],            
            'order_id'      => ['required'],
            'business_id'   => ['required']
        ]);

        $today = Carbon::today();
        $lastQueue = Notification::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
        $number = $lastQueue ? ((int) substr($lastQueue->queue, -3)) + 1 : 1;
        $dayCode = $today->format('D');
        $queueNumber = str_pad($number, 3, '0', STR_PAD_LEFT);
        $queueCode = $dayCode . $queueNumber;

        $notif = Notification::create([      
            'user_id'       => $request->user_id,
            'queue'         => $queueCode,
            'order_id'      => $request->order_id,
            'business_id'   => $request->business_id
        ]);

        return response()->json([
            'success'   => 'Success',
            'message'   => 'Product successfully added',
            'data'      => $notif
        ]);
    }
}
