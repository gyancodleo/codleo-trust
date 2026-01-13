<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ClientOtpPending
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('client')->check()) {
            return redirect()->route('dashboard');
        }

        if (!$request->session()->get('client_otp_pending')) {
            return redirect()
                ->route('login')
                ->withErrors(['error' => 'Unauthorized OTP access.']);
        }
        return $next($request);
    }
}
