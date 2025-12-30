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
    ->withMiddleware(function (Middleware $middleware) {
        // 🟢 FIX: Register the 'admin' alias so protected routes can resolve

$middleware->web(append: [
        \App\Http\Middleware\HandleReferral::class,
    ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // 🟢 FEATURE: Append the status tracker to the global middleware stack
        $middleware->append(\App\Http\Middleware\UpdateAdminStatus::class);

        // 🟢 NEW FEATURE: Append the referral handler to capture 'ref' codes from URLs
        $middleware->append(\App\Http\Middleware\HandleReferral::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();