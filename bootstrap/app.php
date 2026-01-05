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
        // Global web middleware
        $middleware->web(append: [
            \App\Http\Middleware\HandleReferral::class,
        ]);

        // Define Aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.single_session' => \App\Http\Middleware\EnsureSingleAdminSession::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class, // Added for redundancy
        ]);

        // Appending Status and Referral trackers globally
        $middleware->append(\App\Http\Middleware\UpdateAdminStatus::class);
        $middleware->append(\App\Http\Middleware\HandleReferral::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();