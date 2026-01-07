<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $client = ClientUser::find(Session::get('pending_client_id'));

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

    public function resendOtp(OtpService $otpService)
    {
        $clientID = ClientUser::find(Session::get('pending_client_id'));

        if (!$clientID) {
            return redirect()->route('client.login');
        }

        $client = ClientUser::find($clientID);

        $otp = $otpService->generate($client, 'client');

        Session::put('pending_client_id', $client->id);

        try {

            Mail::raw("Your OTP is: $otp", function ($msg) use ($client) {
                $msg->to($client->email)->subject("Login OTP");
            });

            return response()->json(['status' => 'success', 'message' => 'OTP resent successfully']);
        } catch (\Throwable $e) {
            Log::error('OTP resend failed', [
                'admin_id' => $client->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP.',
            ], 500);
        }
    }
}
