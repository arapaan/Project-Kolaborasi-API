<?php

namespace App\Http\Controllers;

use App\Models\Product_variant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function show ($product) {
        $productV = Product_variant::where('product_id', $product)
                    ->with('product')
                    ->get();
        if(!$productV) {
            return response()->json([
                'status'    => 'Error',
                'message'   => 'Product Variant Not Found'
            ]);
        }
        return response()->json([
            'status'    => 'Success',
            'message'   => 'Product Variant retrieved successfully',
            'data'      => $productV
        ]);
    }

    public function __invoke(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string'],
            'product_id'    => ['required'],
            'price'         => ['required', 'integer'],
            'business_id'   => ['required']
        ]);

        $ProductV = Product_variant::craete([
            'name'          => $request->name,
            'product_id'    => $request->product_id, 
            'price'         => $request->price,
            'business_id'   => $request->business_id
        ]);

        return response()->json([
            'status'    => 'Success',            
            'message'   => 'Variant successfully added',
            'data'      => $ProductV
        ]);
    }
}
