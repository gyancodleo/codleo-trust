<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request, OtpService $otpService)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->withErrors(['email' => 'Invalid Credentials']);
        }

        if ($admin->is_2fa_enabled) {

            $otp = $otpService->generate($admin, 'admin');

            Session::put('pending_admin_id', $admin->id);

            Mail::raw("Your Admin OTP is: $otp", function ($msg) use ($admin) {
                $msg->to($admin->email)->subject("Admin Login OTP");
            });

            return redirect()->route('admin.otp.form');
        }

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.dashboard');
    }
}
