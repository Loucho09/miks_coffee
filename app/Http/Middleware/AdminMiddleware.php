<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If NOT logged in -> Go to Login Page
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. If Logged in BUT NOT Admin -> Kick to Home Page
        if (Auth::user()->usertype !== 'admin') {
            return redirect()->route('home');
        }

        // 3. If Admin -> Let them in
        return $next($request);
    }
}