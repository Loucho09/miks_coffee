<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Handle the receipt generation.
     * 🟢 FIXED: Added __invoke to make the class a valid "Invokable" route action.
     */
    public function __invoke($id)
    {
        /** @var User $user */
        $user = Auth::user();

        // Load order with items and products
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        // Security: Ensure user owns the order OR is an admin
        if ($order->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access to this receipt.');
        }

        // Feature: Logic for the "Loyalty Tier" badge
        $pts = $order->user->loyalty_points ?? 0;
        $tier = $pts > 500 ? 'Gold' : ($pts > 200 ? 'Silver' : 'Bronze');

        return view('emails.order_receipt', compact('order', 'tier'));
    }
}