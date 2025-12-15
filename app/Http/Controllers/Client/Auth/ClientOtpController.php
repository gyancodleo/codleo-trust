<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\client_users;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ClientOtpController extends Controller
{
    public function ShowOtpForm()
    {
        if (!Session::has('pending_client_id')) {
            return redirect()->route('client.login');
        }

        return view('client.auth.otp');
    }

    public function verifyOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $client = client_users::find(Session::get('pending_client_id'));

        if (!$client) {
            return redirect()->route('client.login');
        }

        if (!$otpService->validate($client, 'client', $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired otp']);
        }

        Auth::guard('client')->login($client);

        Session::forget('pending_client_id');

        return redirect()->route('client.dashboard');
    }
}
