<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use carbon\Carbon;

class OtpService
{
    public function generate($user, $userType)
    {
        TwoFactorCode::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->delete();

        $otp = rand(100000, 999999);

        TwoFactorCode::create([
            'user_id'    => $user->id,
            'user_type'  => $userType,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        return $otp;
    }

    public function validate($user, $userType, $enteredOtp)
    {
        $code = TwoFactorCode::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->where('otp', $enteredOtp)
            ->first();

        if (!$code) {
            return false; // wrong OTP
        }

        if ($code->expires_at < now()) {
            $code->delete();
            return false; // expired
        }

        // OTP valid → delete it
        $code->delete();

        return true;
    }
}
