<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TrackSessionHistory
{
    /**
     * Standard handle method - just passes the request through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * CLOUD-PROOF LOGIC: Runs AFTER response to prevent interfering with performance-critical routes.
     */
    public function terminate(Request $request, Response $response): void
    {
        // 1. EXCLUDE scan route and security heartbeat to prevent scan timeouts
        if (Auth::check() && !$request->routeIs(['auth.check', 'admin.scan_star_id'])) {
            try {
                // 2. Safely verify column existence before execution to prevent SQL 500 errors
                if (Schema::hasColumn('login_histories', 'session_id')) {
                    $sessionId = $request->session()->getId();
                    
                    if (empty($sessionId)) {
                        $sessionId = 'cloud_sync_' . Str::random(40);
                    }

                    LoginHistory::updateOrCreate(
                        ['session_id' => $sessionId],
                        [
                            'user_id'    => Auth::id(),
                            'ip_address' => $request->ip() ?? '0.0.0.0',
                            'user_agent' => $request->userAgent() ?? 'Unknown',
                            'login_at'   => now(),
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error("Cloud Tracking Glitch: " . $e->getMessage());
            }
        }
    }
}