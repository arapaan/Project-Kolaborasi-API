<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string'],
            'email'     => ['required', 'email', 'string'],
            'password'  => ['required', 'password'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        $tokenResult = $user->createToken('auth_token');
        $token = $tokenResult->plainTextToken;

        $tokenModel = $tokenResult->accesToken;
        $tokenModel->expires_at = Carbon::now()->addHour();
        $tokenModel->save();

        return response()->json([
            'status'    => true,
            'message'   => 'User successfully added',
            'data'      => $user,
            'token'     => $token,
        ], 201);
    }
}
