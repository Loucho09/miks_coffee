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
            $itemTotal = (float) $details['price'];
            if (isset($details['customizations']) && is_array($details['customizations'])) {
                foreach ($details['customizations'] as $addonPrice) {
                    $itemTotal += (float) $addonPrice;
                }
            }
            $subtotal += $itemTotal * $details['quantity'];
        }

        $pointsToRedeem = 50;

        return DB::transaction(function () use ($request, $user, $cart, $subtotal, $pointsToRedeem) {
            $discount = 0;

            if ($request->has('redeem_points') && $user->loyalty_points >= $pointsToRedeem) {
                $user->loyalty_points -= $pointsToRedeem;
                $user->save();
                $discount = 50;

                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$pointsToRedeem,
                    'description' => "Redeemed 50 points for discount",
                ]);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => max(0, $subtotal - $discount),
                'status' => 'pending',
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);

            foreach ($cart as $id => $details) {
                $rawId = isset($details['product_id']) ? $details['product_id'] : $id;
                $productId = (int) (is_string($rawId) ? explode('_', $rawId)[0] : $rawId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'], 
                    'size' => $details['size'] ?? 'Standard',
                    'customizations' => $details['customizations'] ?? null,
                ]);

                $product = Product::find($productId);
                if ($product) {
                    $product->stock_quantity -= $details['quantity'];
                    $product->save();
                }
            }

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
            $user->loyalty_points += 10;
            $user->save();
=======
            // 🟢 EARNING LOGIC
            $user->increment('loyalty_points', 10);
            
>>>>>>> parent of 54976f8 (Dynamic Happy Hour & Revenue Engine)
=======
            // 🟢 EARNING LOGIC
            $user->increment('loyalty_points', 10);
            
>>>>>>> parent of 54976f8 (Dynamic Happy Hour & Revenue Engine)
=======
            // 🟢 EARNING LOGIC
            $user->increment('loyalty_points', 10);
            
>>>>>>> parent of 54976f8 (Dynamic Happy Hour & Revenue Engine)
=======
            $user->loyalty_points += 10;
            $user->save();
>>>>>>> parent of 080ec9b (Merge branch 'main' of https://github.com/Loucho09/miks_coffee)
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