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
        // Register globally in the web group
        $middleware->web(append: [
            \App\Http\Middleware\UpdateAdminStatus::class,
            \App\Http\Middleware\HandleReferral::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'admin.single_session' => \App\Http\Middleware\EnsureSingleAdminSession::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // STRIKE TEAM PRIORITY: Forces the timeout check to run before route security
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Auth\Middleware\Authenticate::class,
            \App\Http\Middleware\UpdateAdminStatus::class, // Must be early
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\EnsureSingleAdminSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();