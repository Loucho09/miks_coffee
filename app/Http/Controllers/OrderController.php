<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceipt;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product', 'items.review'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
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

        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        $claimed = session()->get('claimed_reward');
        
        $discount = 0;
        $pointsRedeemed = 0;
        $rewardType = null;

        // 🟢 SECURITY FIX: Block redemption if points are insufficient
        if ($claimed) {
            if (($user->loyalty_points ?? 0) < $claimed['points']) {
                session()->forget('claimed_reward');
                return redirect()->route('cart.index')->with('error', 'Insufficient points to use this reward.');
            }
            
            $pointsRedeemed = $claimed['points'];
            $rewardType = $claimed['name'];
            $discount = $claimed['value']; 
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Ensure discount doesn't exceed total
        if ($total < $discount) {
            $discount = $total;
        }

        $finalTotal = number_format((float)($total - $discount), 2, '.', '');

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'payment_method' => 'cash',
                'points_earned' => 10,
                'points_redeemed' => $pointsRedeemed,
                'reward_type' => $rewardType,
                'notes' => $rewardType ? "Used Reward: $rewardType" : null,
            ]);

            foreach ($cart as $key => $details) {
                $realProductId = isset($details['product_id']) ? $details['product_id'] : intval($key);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'size' => $details['size'] ?? 'Regular',
                ]);
            }

            // 🟢 POINT DEDUCTION & LOGGING
            if ($pointsRedeemed > 0) {
                $user->decrement('loyalty_points', $pointsRedeemed);
                
                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$pointsRedeemed,
                    'description' => 'Redeemed: ' . $rewardType,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                ]);
            }

            // 🟢 POINT EARNING & LOGGING (+10 for buying)
            $user->increment('loyalty_points', 10);
            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 10,
                'description' => 'Earned from Order #' . $order->id,
                'reference_type' => 'order',
                'reference_id' => $order->id,
            ]);

            DB::commit();
            
            session()->forget(['cart', 'claimed_reward']); 

            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) { }

            return redirect()->route('orders.index')->with('success', 'Order placed! You used ' . $pointsRedeemed . ' pts and earned 10 pts.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
}