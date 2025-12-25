<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; 
use App\Models\PointTransaction;
use App\Models\User;
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
        return redirect()->route('cart.index')->with('success', $request->name . ' applied!');
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return redirect()->back()->with('error', 'Cart is empty!');

        /** @var User $user */
        $user = Auth::user(); 
        $claimed = session()->get('claimed_reward');
        
        $discount = 0; $pointsRedeemed = 0; $rewardType = null;
        if ($claimed) {
            if (($user->loyalty_points ?? 0) < $claimed['points']) {
                session()->forget('claimed_reward');
                return redirect()->route('cart.index')->with('error', 'Insufficient points.');
            }
            $pointsRedeemed = $claimed['points'];
            $rewardType = $claimed['name'];
            $discount = $claimed['value']; 
        }

        $total = 0;
        foreach ($cart as $details) { $total += $details['price'] * $details['quantity']; }
        $finalTotal = number_format((float)($total - $discount), 2, '.', '');

        DB::beginTransaction();
        try {
            foreach ($cart as $key => $details) {
                $productId = isset($details['product_id']) ? $details['product_id'] : intval($key);
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                if (!$product || $product->stock_quantity < $details['quantity']) {
                    throw new \Exception("Insufficient stock for " . ($product->name ?? 'item'));
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'payment_method' => 'cash',
                'points_earned' => 10,
                'points_redeemed' => $pointsRedeemed,
                'reward_type' => $rewardType,
            ]);

            foreach ($cart as $key => $details) {
                $realProductId = isset($details['product_id']) ? $details['product_id'] : intval($key);
                OrderItem::create([
                    'order_id' => $order->id, 'product_id' => $realProductId,
                    'quantity' => $details['quantity'], 'price' => $details['price'],
                    'size' => $details['size'] ?? 'Regular',
                ]);
                Product::find($realProductId)->decrement('stock_quantity', $details['quantity']);
            }

            $user->increment('loyalty_points', 10);
            DB::commit();
            session()->forget(['cart', 'claimed_reward']); 

            return redirect()->route('orders.index')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}