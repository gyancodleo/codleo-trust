<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminOtpController extends Controller
{
    public function ShowOtpForm()
    {
        if (!Session::has('pending_admin_id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.otp');
    }

    public function verifyOtp(Request $request, OtpService $otpService)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $admin = Admin::find(Session::get('pending_admin_id'));

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        if (!$otpService->validate($admin, 'admin', $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired otp']);
        }

        Auth::guard('admin')->login($admin);

        $request->session()->regenerate();

        Session::forget('pending_admin_id');

        return redirect()->route('admin.dashboard');
    }
}
