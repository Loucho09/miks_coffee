<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EnsureSingleAdminSession
{
    /**
     * Local request cache to prevent redundant processing within the same request lifecycle.
     */
    protected static $verified = false;

    /**
     * Handle an incoming request.
     * Enforces that only one Admin session can be active at a time using high-speed cache.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        // SUPER FAST: Exit immediately if no user or user is not an admin
        if (!static::$verified && $user && $user->isAdmin()) {
            $currentSessionId = Session::getId();

            // Fetch session ID from cache instead of DB for speed (TTL 10 mins)
            $cachedSessionId = Cache::remember("admin_session_{$user->id}", 600, function () use ($user) {
                return $user->last_session_id;
            });

            // 🟢 SELF-HEALING: Verify the cached session actually exists before blocking
            if ($cachedSessionId && $currentSessionId !== $cachedSessionId) {
                
                // NEW STEP: Check if that session ID actually exists in the sessions table
                $sessionStillExists = DB::table('sessions')
                    ->where('id', $cachedSessionId)
                    ->exists();

                if (!$sessionStillExists) {
                    // Ghost session detected - clear it and allow continuation
                    $user->update(['last_session_id' => null, 'is_online' => 0]);
                    Cache::forget("admin_session_{$user->id}");
                    
                    // Allow the request to continue - the controller will set a new session
                } else {
                    // Real session exists - block this login attempt
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    Cache::forget("admin_session_{$user->id}");

                    return redirect()->route('login')->with('error', 'Another session is active on this account.');
                }
            }

            // Sync cache if database has it but cache does not
            if (!$cachedSessionId && $user->last_session_id) {
                Cache::put("admin_session_{$user->id}", $user->last_session_id, 600);
            }

            static::$verified = true;
        }

        return $next($request);
    }
}