<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateAdminStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check using the robust isAdmin logic
            if ($user->isAdmin()) {
                /**
                 * SNAP-TIER SYNC:
                 * We use update() to ensure the database record is physically 
                 * changed. This ensures the Green dot is accurate.
                 */
                $user->update([
                    'is_online' => true,
                    'last_seen_at' => now(),
                ]);
            }
        }
        
        return $next($request);
    }
}