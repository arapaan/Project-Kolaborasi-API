<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name_company'  => ['required', 'string', 'min:3'],
            'email'         => ['required', 'email'],            
            'phone'         => ['required', 'integer'],
            'address'       => ['required', 'string'],
            'logo_url'      => ['url'],
        ]);

        $Business = Business::create([
            'name_company'  => $request->name_company,
            'email'         => $request->email,            
            'phone'         => $request->phone,
            'address'       => $request->address,
            'logo_url'      => $request->logo_url,
        ]);

        return response([
            'status'    => true,
            'message'   => 'Business successfully added',
            'data'      => $Business
        ]);
    }
}
