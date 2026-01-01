<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // Admins can bypass specific role checks to access everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Strict role check for non-admins
        // Normalize naming: If the route asks for 'customer', allow users with 'user' or 'customer' type
        $userRole = strtolower($user->role ?? $user->usertype);
        $requiredRole = strtolower($role);

        if ($requiredRole === 'customer' && ($userRole === 'customer' || $userRole === 'user')) {
            return $next($request);
        }

        if ($userRole !== $requiredRole) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}