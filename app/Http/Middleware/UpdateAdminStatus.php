<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class UpdateAdminStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                $currentSessionId = $request->session()->getId();
                
                // 🟢 SELF-HEALING: Verify the stored session ID actually exists
                if ($user->last_session_id && $user->last_session_id !== $currentSessionId) {
                    $sessionStillExists = DB::table('sessions')
                        ->where('id', $user->last_session_id)
                        ->exists();
                    
                    if (!$sessionStillExists) {
                        // Ghost session detected - clear it
                        $user->update([
                            'last_session_id' => $currentSessionId,
                            'last_seen_at' => now(),
                            'is_online' => 1
                        ]);
                        Cache::put("admin_session_{$user->id}", $currentSessionId, 7200);
                    }
                }
                
                $lastSeen = $user->last_seen_at ? Carbon::parse($user->last_seen_at) : null;
                
                // Trigger timeout after 120 minutes of inactivity
                if ($lastSeen && $lastSeen->diffInMinutes(now()) >= 120) {
                    
                    // 1. Wipe database tracking data
                    $user->update([
                        'last_session_id' => null,
                        'last_seen_at' => null,
                        'is_online' => 0
                    ]);
                    
                    Cache::forget("admin_session_{$user->id}");

                    // 2. Force logout and session invalidation
                    Auth::guard('web')->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->withErrors([
                        'email' => 'Security Notice: Session expired after 2 hours of inactivity.'
                    ]);
                }

                // Refresh activity timestamp to extend the 2-hour window
                $user->update(['last_seen_at' => now(), 'is_online' => 1]);
            }
        }

        return $next($request);
    }
}