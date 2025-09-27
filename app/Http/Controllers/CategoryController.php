<?php

namespace App\Http\Controllers;

use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string'],
            'business_id'   => ['required']
        ]);

        $category = category::create([
            'name'          => $request->name,
            'business_id'   => $request->business_id,
            'parent_id'     => $request->parent_id
        ]);

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Category successfully added',
            'data'      => $category
        ]);
    }
}
