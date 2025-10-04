<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string'],
            'category_id'   => ['required'],
            'business_did'  => ['required']
        ]);

        $product = Product::create([
            'name'          => $request->name,
            'category_id'   => $request->category_id,
            'business_id'   => $request->business_id
        ]);

        return response()->json([
            'status'    => true,
            'message'   => 'Product successfully added',
            'data'      => $product,
        ], 201);
    }
}