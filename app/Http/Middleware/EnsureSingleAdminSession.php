<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class EnsureSingleAdminSession
{
    /**
     * Handle an incoming request.
     * Enforces that only one Admin session can be active at a time.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();

        if (Auth::check() && $user->isAdmin()) {
            $currentSessionId = Session::getId();

            // If the user has an active session ID stored and it doesn't match the current one
            if ($user->last_session_id && $currentSessionId !== $user->last_session_id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Another session is active on this account.');
            }

            // If no session is stored yet (first login or session cleared), store it
            if (!$user->last_session_id) {
                $user->last_session_id = $currentSessionId;
                $user->save();
            }
        }

        return $next($request);
    }
}