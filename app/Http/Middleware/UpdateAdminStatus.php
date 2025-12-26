<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateAdminStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if the user is the Admin (by usertype or specific email)
            if ($user->usertype === 'admin' || $user->email === 'jmloucho09@gmail.com') {
                // Update the last activity timestamp
                $user->update(['last_seen_at' => now()]);
            }
        }
        
        return $next($request);
    }
}