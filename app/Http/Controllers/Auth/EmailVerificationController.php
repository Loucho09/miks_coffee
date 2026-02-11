<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function show()
    {
        return view('auth.verify-code');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        /** @var User $user */
        $user = Auth::user();

        if ($request->code == $user->verification_code) {
            $user->markEmailAsVerified();
            $user->update(['verification_code' => null]);
            
            return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
        }

        return back()->withErrors(['code' => 'The verification code is incorrect.']);
    }
}