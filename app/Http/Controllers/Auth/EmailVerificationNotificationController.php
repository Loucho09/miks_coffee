<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification code.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        /** @var \App\Models\User $user */
        $user = $request->user();
        $code = rand(100000, 999999); // Generate new 6-digit code
        
        $user->update(['verification_code' => $code]);

        // 🟢 Send new code via Gmail
        Mail::send([], [], function ($message) use ($user, $code) {
            $message->to($user->email)
                ->subject('Miks Coffee Shop: New Verification Code')
                ->html("<h3>Verification Code</h3><p>Your new 6-digit verification code is: <strong>{$code}</strong></p>");
        });

        return back()->with('status', 'verification-link-sent');
    }
}