<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Log-out Successful',
        ], 201);
    }
}
