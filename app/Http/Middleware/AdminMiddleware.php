<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if the user has administrative privileges using standardized model logic.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If NOT logged in -> Go to Login Page
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Standardized Role Check
        // Uses the fixed isAdmin() method from User.php which handles the email bypass 
        // and the usertype check without crashing on missing columns.
        if ($user->isAdmin()) {
            return $next($request);
        }

        // 3. Unauthorized access -> Redirect to Customer Dashboard
        return redirect()->route('dashboard')->with('error', 'Access Denied: Admin privileges required.');
    } 
}