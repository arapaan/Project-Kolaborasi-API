<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'      => ['required'],
            'email'     => ['required' ,'email'],
            'message'   => ['required']
        ]);

        $feedback = Feedback::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'message'   => $request->message
        ]);

        return response()->json([
            'status'    => 'success',
            'message'   => 'Order successfully added',
            'data'      => $feedback
        ]);
    }
}
