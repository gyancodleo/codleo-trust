<?php

require __DIR__ . '/admin.php';

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Auth\ClientLoginController;
use App\Http\Controllers\Client\Auth\ClientOtpController;
use App\Http\Controllers\Client\ClientPolicyController;


Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
Route::post('/login', [ClientLoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('/', fn () => redirect()->route('client.login'));

Route::get('/otp', [ClientOtpController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/otp/resend', [ClientOtpController::class, 'resendOtp'])->name('otp.resend')->middleware('throttle:2,5');
Route::post('/otp', [ClientOtpController::class, 'verifyOtp'])->name('otp.verify')->middleware('throttle:3,1');

Route::prefix('client')->name('client.')->group(function () {
    Route::middleware('auth.client')->group(function () {
        Route::get('dashboard', [ClientPolicyController::class, 'index'])->name('dashboard');
        Route::get('policies/{policy}/view', [ClientPolicyController::class, 'showViewer'])->name('policies.viewer');

        // Stream the actual PDF bytes (no public URL)
        Route::get('policies/{policy}/stream', [ClientPolicyController::class, 'streamPdf'])->name('policies.stream');

        Route::post('force-logout', function () {
            auth('client')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('client.login');
        })->name('force.logout');
    });
});

require __DIR__ . '/auth.php';
