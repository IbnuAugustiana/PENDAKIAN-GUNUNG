<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IntegrationTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        $expected = 'Bearer ' . env('INTEGRATION_TOKEN');

        if (!$authHeader || trim($authHeader) !== trim($expected)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

