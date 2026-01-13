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

        return view('admin.auth.otp', [
            'otpResendAt' => session('otp_resend_available_at')
        ]);
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

        Session::forget(['pending_admin_id', 'otp_resend_available_at', 'admin_otp_pending']);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function resendOtp(OtpService $otpService)
    {
        $admin = Admin::find(Session::get('pending_admin_id'));

        if (!$admin) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session Expired'
            ], 401);
        }

        try {

            $otp = $otpService->generate($admin, 'admin');

            Mail::raw("Your Admin OTP is: $otp", function ($msg) use ($admin) {
                $msg->to($admin->email)->subject("Admin Login OTP");
            });

            return response()->json(
                ['status' => 'success', 'message' => 'OTP resent successfully', 'cooldown_seconds' => 60]
            );
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            Log::error('OTP resend failed', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend OTP.',
                'remaining_seconds' => $e->getStatusCode() === 429 ? 60 : null
            ], $e->getStatusCode());
        }
    }
}
