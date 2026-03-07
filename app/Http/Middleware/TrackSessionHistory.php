<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TrackSessionHistory
{
    /**
     * Standard handle method - just passes the request through.
     * This ensures the user receives their response (redirect/dashboard) immediately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * CLOUD-PROOF LOGIC: This runs AFTER the browser receives the page.
     * Moving the database write here prevents 500 errors and 419 CSRF timeouts 
     * caused by database lag during the login sequence.
     */
    public function terminate(Request $request, Response $response): void
    {
        // 1. Skip tracking for the security heartbeat route to prevent DB overload
        // 2. Only track if the user is authenticated
        if (Auth::check() && !$request->routeIs('auth.check')) {
            try {
                $sessionId = $request->session()->getId();
                
                if ($sessionId) {
                    // updateOrCreate satisfies the NOT NULL constraint reliably
                    LoginHistory::updateOrCreate(
                        ['session_id' => $sessionId],
                        [
                            'user_id'    => Auth::id(),
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'login_at'   => now(),
                        ]
                    );
                }
            } catch (\Exception $e) {
                // Silently log glitches so the user experience is never interrupted on Laravel Cloud
                Log::error("Cloud Tracking Glitch: " . $e->getMessage());
            }
        }
    }
}