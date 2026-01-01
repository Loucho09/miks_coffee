<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        // NEW FEATURE: Update Admin session ID on login to prevent lockout
        if ($user->isAdmin()) {
            $user->last_session_id = Session::getId();
            $user->save();
        }

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'login_at' => now(),
        ]);
    }
}