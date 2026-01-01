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

        if ($user->isAdmin()) {
            $sessionId = Session::getId();
            
            // Use updateQuietly to prevent triggering unnecessary observers/events
            $user->last_session_id = $sessionId;
            $user->saveQuietly();

            // Update Cache immediately to keep middleware fast
            Cache::put("admin_session_{$user->id}", $sessionId, 600);
        }

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'login_at' => now(),
        ]);
    }
}