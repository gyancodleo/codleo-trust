<?php

namespace App\Services;

use App\Models\TwoFactorCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    protected int $cooldownSeconds = 60;
    protected int $expiryMinutes = 10;
    protected int $maxAttempts = 3;
    protected int $lockMinutes = 15;

    public function generate($user, $userType)
    {
        $record = TwoFactorCode::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->first();

        if ($record && $record->locked_until && now()->lessThan($record->locked_until)) {
            abort(423, 'Too many OTP attempts. Please Try again later.');
        }

        if ($record && $record->expires_at->isFuture()) {

            if (
                $record->otp_last_sent_at &&
                now()->diffInSeconds($record->otp_last_sent_at) < $this->cooldownSeconds
            ) {
                return $record->otp;
            }

            $record->update([
                'otp_last_sent_at' => now(),
            ]);

            return $record->otp;
        }

        $otp = rand(100000, 999999);

        TwoFactorCode::updateOrCreate(
            [
                'user_id' => $user->id,
                'user_type' => $userType,
            ],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes($this->expiryMinutes),
                'otp_last_sent_at' => now(),
                'attempts' => 0,
                'locked_until' => null,
            ]
        );

        return $otp;
    }

    public function validate($user, string $userType, string $enteredOtp): bool
    {
        $record = TwoFactorCode::where([
            'user_id' => $user->id,
            'user_type' => $userType,
        ])->first();

        if (!$record) {
            return false;
        }

        if ($record->locked_until && now()->lessThan($record->locked_until)) {
            abort(423, 'Your Account temporarily locked. Please try again after some time.');
        }

        if ($record->expires_at->isPast()) {
            $record->delete();
            return false;
        }

        if ($record->otp !== $enteredOtp) {
            $record->increment('attempts');

            if ($record->attempts >= $this->maxAttempts) {
                $record->update([
                    'locked_until' => now()->addMinutes($this->lockMinutes),
                ]);
            }

            return false;
        }

        // Success
        $record->delete();
        return true;
    }
}
