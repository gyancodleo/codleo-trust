<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ClientRequestThrottle extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function buildAdminThrottle($key, $maxAttempts)
    {
        return response()->json([
            'status' => 'locked',
            'message' => 'Too many requests. Please login again later.',
            'redirect' => route('login'),
        ], Response::HTTP_TOO_MANY_REQUESTS);
    }
}
