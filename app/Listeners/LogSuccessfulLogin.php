<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class LogSuccessfulLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        $sessionId = Session::getId();

        // Update online status and tracking info
        $user->is_online = true;
        $user->last_seen_at = now();

        if ($user->isAdmin()) {
            $user->last_session_id = $sessionId;
            
            // Update Cache immediately to keep middleware fast
            Cache::put("admin_session_{$user->id}", $sessionId, 600);
        }

        // Use saveQuietly to prevent triggering unnecessary observers/events
        $user->saveQuietly();

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'login_at' => now(),
        ]);
    }
}