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

        // 2. MASTER BYPASS: If email is yours OR usertype is admin, let them in
        if (Auth::user()->email === 'jmloucho09@gmail.com' || Auth::user()->usertype === 'admin') {
            return $next($request);
        }

        // 3. Otherwise, kick to Home Page
        return redirect()->route('home')->with('error', 'Access Denied');
    } 
}