<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ClientLoginController extends Controller
{
    public function showLoginForm()
    {
        if (auth('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return view('client.auth.login');
    }

    public function login(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $client = ClientUser::where('email', $request->email)->first();

        if (!$client || !Hash::check($request->password, $client->password)) {
            return back()->withErrors(['email' => 'Invalid Credentials']);
        }

        if ($client->is_2fa_enabled) {

            $otp = $otpService->generate($client, 'client');

            Session::put('pending_client_id', $client->id);

            Mail::raw("Your OTP is: $otp", function ($msg) use ($client) {
                $msg->to($client->email)->subject("Login OTP");
            });

            return redirect()->route('otp.form');
        }

        Auth::guard('client')->login($client);

        return redirect()->route('client.dashboard');
    }
}
