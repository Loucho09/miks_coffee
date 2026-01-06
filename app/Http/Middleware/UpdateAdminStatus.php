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
                        // Ghost session detected - clear it and link current
                        $user->update([
                            'last_session_id' => $currentSessionId,
                            'last_seen_at' => now(),
                            'is_online' => 1
                        ]);
                        Cache::put("admin_session_{$user->id}", $currentSessionId, 43200); // Extended cache
                        
                        // 🔧 FIX: Force cache refresh for support status
                        Cache::put("support_online_status", true, 43200);
                        Cache::put("admin_online_{$user->id}", true, 43200);
                    }
                }
                
                // 🟢 REMOVED: 120-minute inactivity check/forced logout logic

                // Standard activity update to keep the admin status "Online"
                $user->update([
                    'last_seen_at' => now(), 
                    'is_online' => 1
                ]);
                
                // 🔧 FIX: Update cache to ensure support status matches admin status
                Cache::put("support_online_status", true, 43200);
                Cache::put("admin_online_{$user->id}", true, 43200);
            }
        }

        return $next($request);
    }
}