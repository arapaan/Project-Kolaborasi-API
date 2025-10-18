<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!auth()->check() || !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('employee')) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini!.');
        }
        return $next($request);
    }
}
