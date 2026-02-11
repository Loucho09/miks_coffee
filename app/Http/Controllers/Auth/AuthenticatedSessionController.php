<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = User::where('email', $request->email)->first();

        if ($user && $user->isAdmin() && !empty($user->last_session_id)) {
            $sessionStillExists = DB::table('sessions')
                ->where('id', $user->last_session_id)
                ->exists();

            if (!$sessionStillExists) {
                $user->update(['last_session_id' => null]);
            } else {
                return back()->withErrors([
                    'email' => 'Access Denied: This admin account is already logged in on another device.',
                ]);
            }
        }

        $request->authenticate();
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->isAdmin()) {
            $sessionId = $request->session()->getId();
            $user->update([
                'last_seen_at' => now(),
                'last_session_id' => $sessionId,
                'is_online' => 1
            ]);
            
            Cache::put("admin_session_{$user->id}", $sessionId, 43200); 
            Cache::put("support_online_status", true, 43200);
            Cache::put("admin_online_{$user->id}", true, 43200);
            return redirect()->route('admin.dashboard');
        }

        // Logic for Customer redirection
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.code.view');
        }

        return redirect()->intended('/dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            Cache::forget("admin_session_{$user->id}");
            if ($user->isAdmin()) {
                Cache::forget("support_online_status");
                Cache::forget("admin_online_{$user->id}");
            }
            $user->update(['last_session_id' => null, 'is_online' => 0]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}