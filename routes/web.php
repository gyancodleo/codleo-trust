<?php

require __DIR__ . '/admin.php';

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\Auth\ClientLoginController;
use App\Http\Controllers\Client\Auth\ClientOtpController;
use App\Http\Controllers\Client\ClientPolicyController;


Route::get('/', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
Route::get('/login', [ClientLoginController::class, 'showLoginForm'])->name('client.login');
Route::post('/', [ClientLoginController::class, 'login']);

Route::get('/otp', [ClientOtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp', [ClientOtpController::class, 'verifyOtp'])->name('otp.verify');

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
            return response()->json(['status' => 'logged_out']);
        })->name('force.logout');
    });
});

require __DIR__ . '/auth.php';
