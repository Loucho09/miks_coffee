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
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceipt;
use Illuminate\Support\Str;

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

            // ALTERNATIVE FIX: Generate token here to be used for physical scan
            // Removed automatic +10 point increment to force physical receipt interaction.
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => max(0, $subtotal - $discount),
                'status' => 'pending',
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'qr_claim_token' => Str::random(32), // Secure single-use token
                'points_awarded' => false,
            ]);

            foreach ($cart as $id => $details) {
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

                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock_quantity', $details['quantity']);
                }
            }

            // Dispatch digital receipt email with scannable claim link
            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {
                \Log::error('Receipt Email Failed: ' . $e->getMessage());
            }

            $user->updateStreak();
            session()->forget('cart');

            return redirect()->route('checkout.receipt', $order->id)->with('success', 'Order Sequence Finalized.');
        });
    }

    public function receipt($id)
    {
        $order = Order::with(['items.product', 'user'])->where('user_id', Auth::id())->findOrFail($id);
        return view('cafe.receipt', compact('order'));
    }

    /**
     * Admin claim handler. Points are only awarded once per token.
     * Prevents multi-scanning exploits.
     */
    public function claimOrderPoints($token)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $order = Order::where('qr_claim_token', $token)->firstOrFail();

        if ($order->points_awarded) {
            return redirect()->route('admin.dashboard')->with('error', 'Operational Warning: Stars already claimed for this manifest.');
        }

        DB::transaction(function () use ($order) {
            $customer = $order->user;
            $customer->increment('loyalty_points', 10);

            PointTransaction::create([
                'user_id' => $customer->id,
                'amount' => 10,
                'description' => "Order Manifest Claim: #{$order->order_number}",
            ]);

            $order->update(['points_awarded' => true]);
        });

        return redirect()->route('admin.dashboard')->with('status', "Success! Added 10 Stars to {$order->user->name}. Manifest finalized.");
    }
}