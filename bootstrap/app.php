<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.admin'=> \App\Http\Middleware\AdminAuthenticate::class,
            'auth.client'=>\App\Http\Middleware\ClientUserAuthenticate::class,
            'is.super.admin'=>\App\Http\Middleware\IsSuperAdmin::class,
            'throttle.admin'=>\App\Http\Middleware\AdminRequestThrottle::class,
            'throttle.client'=>\App\Http\Middleware\ClientRequestThrottle::class,
            'client.session'=>\App\Http\Middleware\ClientSession::class,
            'admin.session'=>\App\Http\Middleware\AdminSession::class,
            'admin.otp.pending' => \App\Http\Middleware\AdminOtpPending::class,
            'client.otp.pending' => \App\Http\Middleware\ClientOtpPending::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
