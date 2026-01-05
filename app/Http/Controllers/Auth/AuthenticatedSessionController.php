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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = User::where('email', $request->email)->first();

        // 🟢 THE SELF-HEALING LOGIC: Verify reality before blocking
        if ($user && $user->isAdmin() && !empty($user->last_session_id)) {
            
            // NEW STEP: Check if that session ID actually exists in the sessions table
            $sessionStillExists = DB::table('sessions')
                ->where('id', $user->last_session_id)
                ->exists();

            if (!$sessionStillExists) {
                // If NO → It's a ghost! Clear the ghost ID and allow login
                $user->update(['last_session_id' => null]);
            } else {
                // If YES → It's real! Block login (truly logged in elsewhere)
                return back()->withErrors([
                    'email' => 'Access Denied: This admin account is already logged in on another device.',
                ]);
            }
        }

        // Proceed with standard authentication
        $request->authenticate();
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->isAdmin()) {
            $sessionId = $request->session()->getId();
            
            // Save the new session ID and activity timestamp
            $user->update([
                'last_seen_at' => now(),
                'last_session_id' => $sessionId,
                'is_online' => 1
            ]);
            
            // Set activity timer for the 2-hour idle check
            session(['last_admin_activity' => time()]);
            Cache::put("admin_session_{$user->id}", $sessionId, 7200);
            
            return redirect()->route('admin.dashboard');
        }

        return redirect()->to('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            // 🟢 Proper Cleanup: Clear the session ID on logout
            Cache::forget("admin_session_{$user->id}");
            $user->update([
                'last_session_id' => null, 
                'is_online' => 0
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}