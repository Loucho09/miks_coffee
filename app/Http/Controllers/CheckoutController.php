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
            return redirect()->route('cart.index')->with('error', 'Operational Fault: Cart Empty.');
        }

        // Calculate Subtotal including all categorized add-ons
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

        return DB::transaction(function () use ($request, $user, $cart, $subtotal) {
            $discount = 0;

            // Handle Loyalty Point Redemption
            if ($request->has('redeem_points') && $user->loyalty_points >= 50) {
                $user->loyalty_points -= 50;
                $user->save();
                $discount = 50;

                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -50,
                    'description' => "Redeemed 50 points for discount",
                ]);
            }

            // Initialize Order Manifest Record
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => max(0, $subtotal - $discount),
                'status' => 'pending',
                'order_number' => 'ORD-' . strtoupper(uniqid()),
            ]);

            // Process Manifest Units
            foreach ($cart as $id => $details) {
                // Ensure numeric product identification
                $rawId = isset($details['product_id']) ? $details['product_id'] : $id;
                $productId = (int) (is_string($rawId) ? explode('_', str_replace('itm_', '', $rawId))[0] : $rawId);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'product_name' => $details['name'],
                    'quantity' => $details['quantity'],
                    'price' => (float)$details['price'], 
                    'size' => $details['size'] ?? 'Standard',
                    'customizations' => $details['customizations'] ?? null,
                ]);

                // Reduce Inventory Stock
                $product = Product::find($productId);
                if ($product) {
                    $product->stock_quantity -= $details['quantity'];
                    $product->save();
                }
            }

            // Reward Earning Logic
            $user->loyalty_points += 10;
            $user->save();

            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 10,
                'description' => "Earned from Order #{$order->id}",
            ]);

            // Finalize Session State
            $user->updateStreak();
            session()->forget('cart');

            return redirect()->route('dashboard')->with('success', 'Transaction Sequence Finalized.');
        });
    }
}