<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        
        $cart = session('cart');

        if (!$cart || count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $discount = 0;
        $pointsToRedeem = 50;

        return DB::transaction(function () use ($request, $user, $cart, $subtotal, $pointsToRedeem) {
            $discount = 0;

            // 🟢 REDEMPTION LOGIC
            if ($request->has('redeem_points') && $user->loyalty_points >= $pointsToRedeem) {
                $user->decrement('loyalty_points', $pointsToRedeem);
                $discount = 50;

                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$pointsToRedeem,
                    'description' => "Redeemed 50 points for discount",
                ]);
            }

            // Create the Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => max(0, $subtotal - $discount),
                'status' => 'pending',
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);

            // Save items to database and update stock
            foreach ($cart as $id => $details) {
                // 🟢 FIXED ALTERNATIVE: Extract integer ID from key or internal value
                $rawId = isset($details['product_id']) ? $details['product_id'] : $id;
                
                // If rawId is "2_Standard", this converts it to integer 2
                $productId = (int) (is_string($rawId) ? explode('_', $rawId)[0] : $rawId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId, // Now strictly a numeric integer
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'size' => $details['size'] ?? 'Standard',
                ]);

                // Reduce Stock
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock_quantity', $details['quantity']);
                }
            }

            // 🟢 EARNING LOGIC
            $user->increment('loyalty_points', 10);
            
            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 10,
                'description' => "Earned from Order #{$order->id}",
            ]);

            $user->updateStreak();
            session()->forget('cart');

            return redirect()->route('dashboard')->with('success', 'Order placed successfully! 10 points earned.');
        });
    }
}