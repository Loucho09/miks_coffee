<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = $request->user();

        // Admin redirection and Status Update
        if ($user->usertype === 'admin' || $user->email === 'jmloucho09@gmail.com') {
            $user->update(['last_seen_at' => now()]);
            return redirect()->route('admin.dashboard');
        }

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

        // Set status to Offline immediately on logout
        if ($user && ($user->usertype === 'admin' || $user->email === 'jmloucho09@gmail.com')) {
            $user->update(['last_seen_at' => null]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}