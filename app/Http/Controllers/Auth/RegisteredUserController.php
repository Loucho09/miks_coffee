<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser && $existingUser->email_verified_at) {
            $request->validate([
                'email' => ['unique:'.User::class],
            ]);
        }

        $code = rand(100000, 999999);

        if ($existingUser) {
            $existingUser->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'verification_code' => $code,
            ]);
            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'usertype' => 'customer',
                'verification_code' => $code,
            ]);
        }

        Mail::send('emails.verify-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify Your Mik\'s Coffee Account');
        });

        Auth::login($user);

        return redirect()->route('verification.code.view');
    }
}