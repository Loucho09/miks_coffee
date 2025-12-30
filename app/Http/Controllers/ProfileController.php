<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * 🟢 NEW FEATURE: Display Human-Readable Data Report.
     */
    public function showDataReport(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $orders = $user->orders()->with('items.product')->latest()->get();
        $transactions = $user->pointTransactions()->latest()->get();

        return view('profile.data-report', compact('user', 'orders', 'transactions'));
    }

    /**
     * Export Personal Data for Privacy Compliance.
     */
    public function exportData(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = [
            'identity' => [
                'name' => $user->name,
                'email' => $user->email,
                'member_since' => $user->created_at->toDateTimeString(),
                'referral_code' => $user->referral_code,
            ],
            'loyalty_stats' => [
                'current_points' => $user->points,
                'streak_count' => $user->streak_count,
                'last_order_date' => $user->last_visit_at ? $user->last_visit_at->toDateTimeString() : null,
            ],
            'order_history' => $user->orders()->with('items.product')->get()->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'date' => $order->created_at->toDateTimeString(),
                    'items' => $order->items->map(function($i) {
                         return ($i->product->name ?? 'Brew') . ' (' . $i->quantity . ')';
                    }),
                ];
            }),
            'point_transactions' => $user->pointTransactions()->get()->map(function ($tx) {
                return [
                    'amount' => $tx->amount,
                    'description' => $tx->description,
                    'date' => $tx->created_at->toDateTimeString(),
                ];
            }),
        ];

        $fileName = 'miks-coffee-data-' . $user->id . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $fileName, ['Content-Type' => 'application/json']);
    }
}