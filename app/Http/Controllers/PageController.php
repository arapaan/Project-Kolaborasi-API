<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('sections')->get();

        return response()->json([
            'status'    => 'Success',
            'message'   => 'Pages retrieved successfully',
            'data'      => $pages
        ]);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)
        ->with('sections')
        ->first();

        if (!$page)
        {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Page Not Found',
            ], 404);
        }

        return response()->json([
            'status'    => 'success',
            'message'   => 'Page retrieved successfully',
            'data'      => $page
        ]);
    }
}
