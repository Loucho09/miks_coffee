<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminIdleTimeout
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->usertype === 'admin') {
            $lastActivity = session('last_admin_activity');
            $timeout = 120 * 60; // 120 minutes in seconds

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('status', 'Session expired due to inactivity.');
            }

            // Update activity timestamp
            session(['last_admin_activity' => time()]);
        }

        return $next($request);
    }
}