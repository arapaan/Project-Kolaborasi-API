<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'business_id'   => ['required'],
            'discount'      => ['required'],
            'expires_date'  => ['required', 'date']
        ]);

        $Disc = Discount::create([
            'business_id'   => $request->business_id,
            'discount'      => $request->discount,
            'expires_date'  => $request->expires_date
        ]);

        Return response()->json([
            'status'    => 'Success',
            'message'   => 'Discount successfully added',
            'data'      => $Disc
        ]);
    }
}
