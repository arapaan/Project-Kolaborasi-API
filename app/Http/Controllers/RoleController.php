<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string'],
            'business_id'   => ['required']
        ]);

        $role = Role::create([
            'name'          => $request->name,
            'business_id'   => $request->business_id
        ]);

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Role successfully added',
            'data'      => $role
        ]);
    }
}
