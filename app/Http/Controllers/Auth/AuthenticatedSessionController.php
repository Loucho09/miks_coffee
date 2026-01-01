<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
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
        // Check if the admin is already logged in on another device before authenticating
        $user = User::where('email', $request->email)->first();

        if ($user && $user->isAdmin() && !empty($user->last_session_id)) {
            return back()->withErrors([
                'email' => 'Access Denied: This admin account is already logged in on another device. Concurrent sessions are restricted for admins.',
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // DIRECT SYNC: Force status update on login
        $user->is_online = true;
        $user->last_seen_at = now();
        
        if ($user->isAdmin()) {
            $sessionId = $request->session()->getId();
            $user->last_session_id = $sessionId;
            $user->save(); // Commit to DB

            Cache::put("admin_session_{$user->id}", $sessionId, 3600);
            return redirect()->route('admin.dashboard');
        }

        $user->save(); // Commit to DB for Customer

        // Customer redirection
        return redirect()->to('/dashboard')->with('login_success', "Welcome back, {$user->name}! Brew something special today.");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // DIRECT SYNC: Force status to offline before session destruction
        if ($user) {
            Cache::forget("admin_session_{$user->id}");
            
            $user->is_online = false;
            $user->last_session_id = null;
            $user->last_seen_at = now();
            $user->save(); // Commit to DB
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}