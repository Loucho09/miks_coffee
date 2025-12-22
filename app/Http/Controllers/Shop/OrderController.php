<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // 🟢 Required for stock deduction
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceipt;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        return view('cafe.orders', compact('orders'));
    }

    public function claimReward(Request $request)
    {
        $reward = [
            'name'   => $request->name,
            'points' => $request->points,
            'value'  => $request->value,
            'type'   => $request->type
        ];
        
        session()->put('claimed_reward', $reward);
        return redirect()->route('cart.index')
            ->with('success', $request->name . ' applied! Place order to claim.');
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        $discount = 0;
        $pointsRedeemed = 0;
        $rewardType = null;
        $claimed = session()->get('claimed_reward');

        if ($claimed) {
            if ($user->points >= $claimed['points']) {
                $pointsRedeemed = $claimed['points'];
                $rewardType = $claimed['name'];
                $discount = $claimed['value']; 
            }
        } elseif ($request->has('redeem_points') && $user->points >= 50) {
            $discount = 50;
            $pointsRedeemed = 50;
            $rewardType = 'Standard Discount';
        }

        if ($total < $discount) {
            $discount = $total;
        }

        $finalTotal = number_format((float)($total - $discount), 2, '.', '');

        DB::beginTransaction();
        try {
            // 🟢 NEW FEATURE: Pre-order Stock Check
            // Prevents checkout if any item in the cart exceeds available stock
            foreach ($cart as $key => $details) {
                $productId = isset($details['product_id']) ? $details['product_id'] : intval($key);
                $product = Product::find($productId);
                if ($product && $product->stock_quantity < $details['quantity']) {
                    return redirect()->back()->with('error', "Sorry, {$product->name} is low on stock.");
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'payment_method' => 'cash',
                'points_earned' => 10,
                'points_redeemed' => $pointsRedeemed,
                'reward_type' => $rewardType,
                'notes' => $rewardType ? "Reward: $rewardType" : null,
            ]);

            foreach ($cart as $key => $details) {
                $realProductId = isset($details['product_id']) ? $details['product_id'] : intval($key);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'product_name' => $details['name'],
                    'size' => $details['size'] ?? null,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                // 🟢 FIXED: Deduct stock from the Product table
                $product = Product::find($realProductId);
                if ($product) {
                    $product->decrement('stock_quantity', $details['quantity']);
                }
            }

            if ($pointsRedeemed > 0) {
                $user->points = $user->points - $pointsRedeemed;
            }
            $user->points = $user->points + 10;
            $user->save();

            DB::commit();
            
            session()->forget('cart');
            session()->forget('claimed_reward'); 

            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {}

            return redirect()->route('orders.index')->with('success', 'Order placed! You earned 10 points.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}