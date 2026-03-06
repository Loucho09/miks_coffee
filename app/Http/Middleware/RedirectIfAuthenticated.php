<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) { // FIX: Added 'as $guard' to fix the syntax error
            if (Auth::guard($guard)->check()) { // FIX: Pass individual $guard instead of $guards array
                /**
                 * FIXED REDIRECT LOGIC: This stops the 500/login loop.
                 * If a user is already authenticated (half-logged in), 
                 * we force them to the home dashboard immediately.
                 */
                return redirect(route('home'));
            }
        }

        return $next($request);
    }
}