<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function show($name) {
        $business = Business::where('name_company', $name)
                    ->first();

        if(!$business) {
            return response()->json([
                'status'    =>  'error',
                'message'   =>  'Business Not Found',
            ]);
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Business retrieved successfully',
            'data'      => $business
        ]);
    }
}
