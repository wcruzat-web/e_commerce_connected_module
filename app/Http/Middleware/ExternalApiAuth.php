<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExternalApiAuth
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $key = $request->bearerToken();
        $expected = config("external-modules.{$module}.api_key");

        if (!$key || !$expected || !hash_equals($expected, $key)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
