<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    protected int $cooldownSeconds = 60;
    protected int $expiryMinutes = 10;
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

    public function generateAndResend(int $userId, string $userType)
    {
        $record = TwoFactorCode::where([
            'user_id' => $userId,
            'user_type' => $userType,
        ])->first();

        if (!$record) {
            return [
                'status' => 'error',
                'message' => 'Unable to send OTP',
            ];
        }

        if ($record->otp_last_sent_at) {
            $remaining = now()->diffInSeconds(
                $record->otp_last_sent_at->addSeconds($this->cooldownSeconds),
                false
            );

            if ($remaining > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Please wait before resending OTP.',
                    'remaining_seconds' => $remaining,
                ];
            }
        }

        $otp = random_int(100000, 999999);

        $record->update([
            'otp'         => $otp,
            'expires_at'   => now()->addMinutes($this->expiryMinutes),
            'otp_last_sent_at' => now(),
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
