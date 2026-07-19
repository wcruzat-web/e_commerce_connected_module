<?php

// [CRUZAT] — redirects admins away from customer routes
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role !== 'customer') {
            return redirect('/admin/dashboard');
        }

        return $next($request);
    }
}
