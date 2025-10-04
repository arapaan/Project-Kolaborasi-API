<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            '' => ['required', 'string']
            ''
        ]);
    }
}
