<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {        
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'    => 'Error',
                'message'   => 'Incorrect email or password',
            ], 401);
        }

        $user = Auth::user();
        
        $user->tokens()->delete();        

        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->plainTextToken;
    
        $tokenResult->accessToken->forceFill([
            'expires_at' => now()->addHour(),
        ])->save();

        return response()->json([
            'status'        => 'Success',
            'message'       => 'Login Successful',
            'token'         => $token,
            'expires_at'    => $tokenResult->accessToken->expires_at,
            'user'          => $user,
        ]);
    }
}
