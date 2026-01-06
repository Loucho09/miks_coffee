<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use App\Models\User;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $cart = session('cart');

        if (!$cart) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;

        // 🟢 REDEMPTION LOGIC: Deduct 50 PTS from Database
        if ($request->has('redeem_points') && $user->loyalty_points >= 50) {
            $user->decrement('loyalty_points', 50);
            $discount = 50;

            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => -50,
                'description' => "Redeemed 50 points for discount",
            ]);
        }

        // Create the Order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => max(0, $subtotal - $discount),
            'status' => 'pending',
        ]);

        // Save items to database
        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
                'size' => $details['size'] ?? 'Standard',
            ]);
        }

        // 🟢 EARNING LOGIC: Add +10 PTS for this order
        $user->increment('loyalty_points', 10);
        
        PointTransaction::create([
            'user_id' => $user->id,
            'amount' => 10,
            'description' => "Earned from Order #{$order->id}",
        ]);

        // Update streak if needed
        $user->updateStreak();

        // Clear cart session
        session()->forget('cart');

        return redirect()->route('dashboard')->with('success', 'Order placed successfully! 10 points earned.');
    }
}