<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\AdminOtpController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\Auth\Logout;
use App\Http\Controllers\Admin\PolciyCategoryController;
use App\Http\Controllers\Admin\AdminUserCreationController;
use App\Http\Controllers\Admin\ClientUserCreationController;
use App\Http\Controllers\Admin\AssignPoliciesController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/otp', [AdminOtpController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/otp', [AdminOtpController::class, 'verifyOtp'])->name('otp.verify')->middleware('throttle:5,1');

    Route::post('/otp/resend', [AdminOtpController::class, 'resendOtp'])->name('otp.resend')->middleware('throttle:2,5');

    Route::post('/logout', Logout::class)->middleware('auth.admin')->name('logout');

    Route::middleware('auth.admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        //policy crud routes
        Route::get('policies/policy', [PolicyController::class, 'index'])->name('policy');
        Route::get('policies/create', [PolicyController::class, 'create'])->name('policy.create');
        Route::post('policies/create', [PolicyController::class, 'store']);
        Route::get('policies/edit', [PolicyController::class, 'edit']);
        Route::put('policies/edit', [PolicyController::class, 'update']);
        Route::delete('policies/delete', [PolicyController::class, 'destroy']);

        // Publish / Unpublish
        Route::post('policies/{policy}/publish', [PolicyController::class, 'publish'])->name('policies.publish');
        Route::post('policies/{policy}/unpublish', [PolicyController::class, 'unpublish'])->name('policies.unpublish');

        // stream / view
        Route::get('/policies/{policy}/stream', [PolicyController::class, 'stream'])->name('policy.stream');
        Route::get('/policies/{policy}/preview', [PolicyController::class, 'preview'])->name('policy.preview');

        // policy category
        Route::get('policies/category', [PolciyCategoryController::class, 'index'])->name('policy.category');
        Route::post('policies/category', [PolciyCategoryController::class, 'store'])->name('policy.category.store');
        Route::put('policies/category/{category}', [PolciyCategoryController::class, 'updateCategory'])->name('policy.category.update');
        Route::delete('policies/category/{category}', [PolciyCategoryController::class, 'destroy'])->name('policy.category.delete');

        
        Route::post('policies/assingPolicy', [AssignPoliciesController::class, 'store'])->name('policy.assign');
        Route::get('clients/{client}/assigned-policies', [AssignPoliciesController::class, 'getAssignedPolicies'])->name('clients.assigned-policies');
        Route::get('clients/{client}/assigned-policies-ids', [AssignPoliciesController::class, 'getAssignedPolicyIds'])->name('clients.assigned.policy.ids');

        Route::resource('policies', PolicyController::class);

        Route::middleware('is.super.admin')->group(function () {
            Route::get('users', [AdminUserCreationController::class, 'index'])->name('users.index');
            Route::post('users', [AdminUserCreationController::class, 'store'])->name('users.store');
            Route::put('users/{user}', [AdminUserCreationController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [AdminUserCreationController::class, 'destroy'])->name('users.destroy');
        });

        Route::get('clients', [ClientUserCreationController::class, 'index'])->name('clients.index');
        Route::post('clients', [ClientUserCreationController::class, 'store'])->name('clients.store');
        Route::put('clients/{client}', [ClientUserCreationController::class, 'update'])->name('clients.update');
        Route::delete('clients/{client}', [ClientUserCreationController::class, 'destroy'])->name('clients.destroy');
    });
});
