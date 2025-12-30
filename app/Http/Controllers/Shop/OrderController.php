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
use Illuminate\Support\Facades\Log;
use App\Mail\OrderReceipt;
use App\Mail\LowStockAlert;

class OrderController extends Controller
{
    /**
     * Display customer order history.
     */
    public function index()
    {
        $orders = Order::with(['items.product', 'items.review'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('cafe.orders', compact('orders'));
    }

    /**
     * Apply a reward to the session.
     */
    public function claimReward(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $rewards = [
            'free_espresso' => ['name' => 'Signature Espresso', 'cost' => 50],
            'pastry_treat'  => ['name' => 'Artisan Pastry', 'cost' => 80],
            'premium_brew'  => ['name' => 'Large Premium Brew', 'cost' => 120],
            'bag_beans'     => ['name' => 'House Blend (250g)', 'cost' => 500],
        ];

        $rewardKey = $request->input('reward_id');

        if ($rewardKey && isset($rewards[$rewardKey])) {
            $reward = $rewards[$rewardKey];

            if (($user->points ?? 0) < $reward['cost']) {
                return back()->with('error', 'Insufficient points for this redemption.');
            }

            DB::transaction(function () use ($user, $reward) {
                $user->decrement('points', $reward['cost']);

                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$reward['cost'],
                    'description' => 'Redeemed: ' . $reward['name'],
                ]);
            });

            return back()->with('success', 'Redemption authorized! Please present your terminal to the barista.');
        }

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

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        /** @var User $user */
        $user = Auth::user(); 
        $claimed = session()->get('claimed_reward');
        
        $pointsDiscount = 0;
        $pointsRedeemed = 0;
        $rewardType = null;

        if ($claimed) {
            if (($user->points ?? 0) < $claimed['points']) {
                session()->forget('claimed_reward');
                return redirect()->route('cart.index')->with('error', 'Insufficient points.');
            }
            
            $pointsRedeemed = $claimed['points'];
            $rewardType = $claimed['name'];
            $pointsDiscount = $claimed['value']; 
        } elseif ($request->has('redeem_points') && ($user->points ?? 0) >= 50) {
            $pointsDiscount = 50;
            $pointsRedeemed = 50;
            $rewardType = 'Standard Discount';
        }

        $subtotal = 0;
        $totalItemsInCart = 0;
        
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
            $totalItemsInCart += $details['quantity'];
        }

        $bulkSavings = 0;
        if ($totalItemsInCart >= 6) {
            $bulkSavings = $subtotal * 0.10;
        }

        $finalSubtotal = $subtotal - $bulkSavings;

        if ($finalSubtotal < $pointsDiscount) {
            $pointsDiscount = $finalSubtotal;
        }

        $finalTotal = number_format((float)(max(0, $finalSubtotal - $pointsDiscount)), 2, '.', '');

        DB::beginTransaction();
        try {
            foreach ($cart as $key => $details) {
                $productId = $details['product_id'] ?? intval($key);
                $product = Product::where('id', $productId)->lockForUpdate()->first();
                
                if (!$product || $product->stock_quantity < $details['quantity']) {
                    throw new \Exception("Sorry, " . ($product->name ?? 'item') . " is low on stock.");
                }
            }

            $noteArr = [];
            if ($rewardType) $noteArr[] = "Reward: $rewardType";
            if ($bulkSavings > 0) $noteArr[] = "Bulk Cart Discount (10%): ₱" . number_format($bulkSavings, 2);
            $finalNotes = implode(' | ', $noteArr);

            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->customer_name ?? $user->name,
                'customer_email' => $request->customer_email ?? $user->email,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'payment_method' => 'cash',
                'points_earned' => 10,
                'points_redeemed' => $pointsRedeemed,
                'reward_type' => $rewardType,
                'notes' => $finalNotes ?: null,
            ]);

            foreach ($cart as $key => $details) {
                $realProductId = $details['product_id'] ?? intval($key);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'product_name' => $details['name'] ?? null,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'size' => $details['size'] ?? 'Regular',
                ]);

                $product = Product::find($realProductId);
                $product->decrement('stock_quantity', $details['quantity']);

                if ($product->stock_quantity < 5) {
                    try {
                        Mail::to('admin@mikscoffee.com')->send(new LowStockAlert($product));
                    } catch (\Exception $e) {
                        Log::error("Low Stock Mail Failed: " . $e->getMessage());
                    }
                }
            }

            if ($user->referred_by && $user->orders()->count() === 1) {
                $referrer = $user->referrer;
                if ($referrer) {
                    $referrer->increment('points', 50);
                    PointTransaction::create([
                        'user_id' => $referrer->id,
                        'amount' => 50,
                        'description' => 'Referral Bonus: ' . $user->name . ' first order',
                        'order_id' => $order->id
                    ]);

                    $user->increment('points', 50);
                    PointTransaction::create([
                        'user_id' => $user->id,
                        'amount' => 50,
                        'description' => 'Welcome Referral Bonus from ' . $referrer->name,
                        'order_id' => $order->id
                    ]);
                }
            }

            if ($pointsRedeemed > 0) {
                $user->decrement('points', $pointsRedeemed);
                PointTransaction::create([
                    'user_id' => $user->id,
                    'amount' => -$pointsRedeemed,
                    'description' => 'Checkout Redemption: ' . $rewardType,
                    'order_id' => $order->id
                ]);
            }

            $user->increment('points', 10);
            PointTransaction::create([
                'user_id' => $user->id,
                'amount' => 10,
                'description' => 'Earned from Order #' . $order->id,
                'order_id' => $order->id
            ]);

            // 🟢 UPDATED: Let the Model handle the streak and milestone bonus points automatically
            $user->updateStreak();

            DB::commit();
            session()->forget(['cart', 'claimed_reward']); 

            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {}

            return redirect()->route('dashboard')->with('success', 'Order established! Bulk discount and streak progress applied.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export sales data for admins.
     */
    public function exportData()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $orders = Order::with(['user'])->latest()->get();
        $fileName = 'miks_coffee_sales_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer', 'Amount', 'Date', 'Status', 'Performance']);

            foreach ($orders as $order) {
                $performance = ($order->total_price >= 500) ? 'HIGH VALUE' : 'Standard';

                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Guest',
                    '₱' . number_format($order->total_price, 2),
                    $order->created_at->format('Y-m-d H:i'),
                    ucfirst($order->status),
                    $performance
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the order receipt.
     */
    public function downloadReceipt($id)
    {
        /** @var User $user */
        $user = Auth::user();
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        if ($order->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $pts = $user->points ?? 0;
        $tier = $pts >= 500 ? 'Gold' : ($pts >= 200 ? 'Silver' : 'Bronze');

        return view('emails.order_receipt', compact('order', 'tier'));
    }
}