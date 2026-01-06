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

        // 🟢 STEP 1: SELF-HEALING LOGIC
        // This checks if a "Ghost Session" exists before blocking the login.
        if ($user && $user->isAdmin() && !empty($user->last_session_id)) {
            
            // Check if the old session ID actually exists in the database physical table
            $sessionStillExists = DB::table('sessions')
                ->where('id', $user->last_session_id)
                ->exists();

            if (!$sessionStillExists) {
                // If the session record is missing, it's a ghost: clear it automatically
                $user->update(['last_session_id' => null]);
            } else {
                // If it is TRULY alive on another device, block duplicate login
                return back()->withErrors([
                    'email' => 'Access Denied: This admin account is already logged in on another device.',
                ]);
            }
        }

        // Standard Laravel authentication
        $request->authenticate();
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = $request->user();

        if ($user->isAdmin()) {
            $sessionId = $request->session()->getId();
            
            // Update activity tracking
            $user->update([
                'last_seen_at' => now(),
                'last_session_id' => $sessionId,
                'is_online' => 1
            ]);
            
            // 🟢 STEP 2: REMOVE 2H RESTRICTION
            // We set a long cache duration (12 hours) instead of the old 7200 seconds.
            Cache::put("admin_session_{$user->id}", $sessionId, 43200); 
            
            // 🔧 FIX: Set support status cache to match admin online status
            Cache::put("support_online_status", true, 43200);
            Cache::put("admin_online_{$user->id}", true, 43200);
            
            // Maintain activity timestamp for display purposes only
            session(['last_admin_activity' => time()]);
            
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
            // Cleanup tracking data on proper logout
            Cache::forget("admin_session_{$user->id}");
            
            // 🔧 FIX: Clear support status cache when admin logs out
            if ($user->isAdmin()) {
                Cache::forget("support_online_status");
                Cache::forget("admin_online_{$user->id}");
            }
            
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