<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

    public function resendOtp(OtpService $otpService)
    {
        $adminId = Admin::find(Session::get('pending_admin_id'));

        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::find($adminId);

        $otp = $otpService->generateAndResend($admin->id, 'admin');

        Session::put("pending_admin_id", $admin->id);

        try {

            Mail::raw("Your Admin OTP is: $otp", function ($msg) use ($admin) {
                $msg->to($admin->email)->subject("Admin Login OTP");
            });

            return response()->json(['status' => 'success', 'message' => 'OTP resent successfully']);
        } catch (\Throwable $e) {
            Log::error('OTP resend failed', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP.',
            ], 500);
        }
    }
}
