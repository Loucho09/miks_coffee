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
        
        $discount = 0;
        $pointsRedeemed = 0;
        $rewardType = null;

        if ($claimed) {
            // FIXED: Changed loyalty_points to points to match the 68 points fix
            if (($user->points ?? 0) < $claimed['points']) {
                session()->forget('claimed_reward');
                return redirect()->route('cart.index')->with('error', 'Insufficient points.');
            }
            
            $pointsRedeemed = $claimed['points'];
            $rewardType = $claimed['name'];
            $discount = $claimed['value']; 
        }

        $total = 0;
        foreach ($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $finalTotal = number_format((float)(max(0, $total - $discount)), 2, '.', '');

        DB::beginTransaction();
        try {
            // Stock Check
            foreach ($cart as $key => $details) {
                $productId = $details['product_id'] ?? intval($key);
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
                'notes' => $rewardType ? "Used Reward: $rewardType" : null,
            ]);

            foreach ($cart as $key => $details) {
                $realProductId = $details['product_id'] ?? intval($key);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $realProductId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    'size' => $details['size'] ?? 'Regular',
                ]);
                Product::find($realProductId)->decrement('stock_quantity', $details['quantity']);
            }

            // FIXED: Standardized point column names
            if ($pointsRedeemed > 0) {
                $user->decrement('points', $pointsRedeemed);
            }
            $user->increment('points', 10);

            DB::commit();
            session()->forget(['cart', 'claimed_reward']); 

            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) { }

            // 🟢 REDIRECT CHANGE: Directs to Dashboard instead of Orders Index
           return redirect()->route('dashboard')->with('success', 'Order established successfully! Rate your brew below to claim bonus loyalty points.');
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

        // FIXED: Changed loyalty_points to points to match global sync
        $pts = $user->points ?? 0;
        $tier = $pts >= 500 ? 'Gold' : ($pts >= 200 ? 'Silver' : 'Bronze');

        return view('emails.order_receipt', compact('order', 'tier'));
    }
}