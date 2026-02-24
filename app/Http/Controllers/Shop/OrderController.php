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
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Canonical rewards table — single source of truth used by BOTH
     * claimReward() and store(). Keeping it in one place prevents drift.
     */
    private function rewardsTable(): array
    {
        return [
            'pwr_2'  => ['name' => '2% Discount Power',        'cost' => 200,  'percent' => 2],
            'pwr_5'  => ['name' => '5% Discount Power',        'cost' => 500,  'percent' => 5],
            'pwr_10' => ['name' => '10% Discount Power',       'cost' => 1000, 'percent' => 10],
            'pwr_20' => ['name' => '20% Discount Power (MAX)', 'cost' => 2000, 'percent' => 20],
        ];
    }

    public function index()
    {
        $orders = Order::with(['items.product', 'items.review'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        return view('cafe.orders', compact('orders'));
    }

    // =========================================================================
    // 🟢 REORDER PROTOCOL
    // =========================================================================
    public function reorder($id)
    {
        $order = Order::with('items.product.sizes')->where('user_id', Auth::id())->findOrFail($id);
        $cart  = session()->get('cart', []);

        foreach ($order->items as $item) {
            $product = $item->product;
            if (!$product || !$product->is_active || $product->stock_quantity <= 0) continue;

            $isHHActiveNow    = $product->is_happy_hour_active;
            $currentBasePrice = 0;

            if ($product->sizes->count() > 0) {
                $sizeObj           = $product->sizes->where('size', $item->size)->first();
                $originalBasePrice = $sizeObj ? (float)$sizeObj->price : (float)$product->price;
                $currentBasePrice  = $isHHActiveNow
                    ? ($originalBasePrice * (1 - ($product->happy_hour_discount / 100)))
                    : $originalBasePrice;
            } else {
                $currentBasePrice = $isHHActiveNow ? $product->happy_hour_price : $product->price;
            }

            $customizations    = $item->customizations ?? [];
            $customizationHash = md5(json_encode($customizations));
            $cartKey           = "itm_" . $item->product_id . "_"
                . str_replace([' ', "'"], ['_', ''], $item->size) . "_" . $customizationHash;

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $item->quantity;
            } else {
                $cart[$cartKey] = [
                    "product_id"     => (int)  $item->product_id,
                    "name"           => $product->name,
                    "quantity"       => $item->quantity,
                    "price"          => (float) $currentBasePrice,
                    "size"           => $item->size,
                    "image"          => $product->image,
                    "is_happy_hour"  => $isHHActiveNow,
                    "customizations" => $customizations,
                ];
            }
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Manifest restored with current market valuation.');
    }

    // =========================================================================
    // 🟢 FAVORITE REORDER PROTOCOL
    // =========================================================================
    public function reorderFavorite(Request $request)
    {
        $product = Product::with('sizes')->findOrFail($request->product_id);
        $cart    = session()->get('cart', []);

        if (!$product->is_active || $product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Asset is currently depleted or inactive.');
        }

        $size             = $request->size;
        $isHHActiveNow    = $product->is_happy_hour_active;
        $currentBasePrice = 0;

        if ($product->sizes->count() > 0) {
            $sizeObj           = $product->sizes->where('size', $size)->first();
            $originalBasePrice = $sizeObj ? (float)$sizeObj->price : (float)$product->price;
            $currentBasePrice  = $isHHActiveNow
                ? ($originalBasePrice * (1 - ($product->happy_hour_discount / 100)))
                : $originalBasePrice;
        } else {
            $currentBasePrice = $isHHActiveNow ? $product->happy_hour_price : $product->price;
        }

        $customizations    = json_decode($request->customizations, true) ?? [];
        $customizationHash = md5(json_encode($customizations));
        $cartKey           = "itm_" . $product->id . "_"
            . str_replace([' ', "'"], ['_', ''], $size) . "_" . $customizationHash;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id"     => (int)  $product->id,
                "name"           => $product->name,
                "quantity"       => 1,
                "price"          => (float) $currentBasePrice,
                "size"           => $size,
                "image"          => $product->image,
                "is_happy_hour"  => $isHHActiveNow,
                "customizations" => $customizations,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Favorite asset restored to active manifest.');
    }

    // =========================================================================
    // 🟢 REWARDS MANIFEST
    // =========================================================================
    public function claimReward(Request $request)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $rewards = $this->rewardsTable();

        $rewardKey = $request->input('reward_id');

        if ($rewardKey && isset($rewards[$rewardKey])) {
            $reward = $rewards[$rewardKey];

            if (($user->loyalty_points ?? 0) < $reward['cost']) {
                return back()->with('error', 'Insufficient Loyalty Assets for this power tier.');
            }

            session()->put('claimed_reward', [
                'key'     => $rewardKey,
                'name'    => $reward['name'],
                'points'  => (int)   $reward['cost'],
                'percent' => (float) $reward['percent'],
                'type'    => 'percent_pwr',
            ]);

            session()->save();

            return redirect()->route('cart.index')->with('success', "{$reward['name']} authorized for checkout.");
        }

        return back()->with('error', 'Invalid reward selection.');
    }

    // =========================================================================
    // 🟢 ORDER COMMIT PROTOCOL
    // =========================================================================
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $cart    = session()->get('cart');
        $claimed = session()->get('claimed_reward');

        if (!$cart || count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        if (!$claimed && $request->filled('claimed_reward_key')) {
            $rewards   = $this->rewardsTable();
            $rewardKey = $request->input('claimed_reward_key');

            if (isset($rewards[$rewardKey])) {
                $reward  = $rewards[$rewardKey];
                $claimed = [
                    'key'     => $rewardKey,
                    'name'    => $reward['name'],
                    'points'  => (int)   $reward['cost'],
                    'percent' => (float) $reward['percent'],
                    'type'    => 'percent_pwr',
                ];
            }
        }

        /** @var \App\Models\User $user */
        $user = User::find(Auth::id());

        $subtotal = 0;
        foreach ($cart as $details) {
            $linePrice = (float) $details['price'];
            if (isset($details['customizations']) && is_array($details['customizations'])) {
                foreach ($details['customizations'] as $c) $linePrice += (float) $c;
            }
            $subtotal += $linePrice * $details['quantity'];
        }

        $discountValue   = 0;
        $discountPercent = 0;
        $pointsRedeemed  = 0;
        $rewardType      = null;

        if ($claimed && isset($claimed['type']) && $claimed['type'] === 'percent_pwr') {
            if (($user->loyalty_points ?? 0) >= (int) $claimed['points']) {
                $pointsRedeemed  = (int)   $claimed['points'];
                $discountPercent = (float) $claimed['percent'];
                $rewardType      = $claimed['name'];
            }
        } elseif ($request->has('redeem_points') && $request->input('redeem_points') == 1) {
            if (($user->loyalty_points ?? 0) >= 100) {
                $pointsRedeemed  = 100;
                $discountPercent = 1.0;
                $rewardType      = '1% Instant Discount Power';
            }
        }

        if ($discountPercent > 0) {
            $discountValue = $subtotal * ($discountPercent / 100);
        }

        $finalTotal = (float) number_format((float) max(0, $subtotal - $discountValue), 2, '.', '');

        $pts          = (int) ($user->loyalty_points ?? 0);
        $tier         = $pts >= 500 ? 'Gold' : ($pts >= 200 ? 'Silver' : 'Bronze');
        $multiplier   = $tier === 'Gold' ? 2.0 : ($tier === 'Silver' ? 1.5 : 1.0);
        $pointsEarned = (int) round(10 * $multiplier);

        DB::beginTransaction();
        try {
            foreach ($cart as $details) {
                $product = Product::where('id', $details['product_id'])->lockForUpdate()->first();
                if (!$product || $product->stock_quantity < $details['quantity']) {
                    throw new \Exception("Insufficient inventory for " . ($product->name ?? 'item') . ".");
                }
            }

            $order = Order::create([
                'user_id'         => $user->id,
                'customer_name'   => $user->name,
                'customer_email'  => $user->email,
                'total_price'     => $finalTotal,
                'status'          => 'pending',
                'points_earned'   => $pointsEarned,
                'points_redeemed' => $pointsRedeemed,
                'reward_type'     => $rewardType,
                'order_number'    => 'ORD-' . strtoupper(uniqid()),
            ]);

            foreach ($cart as $details) {
                $itemUnitPrice = (float) $details['price'];
                if (isset($details['customizations']) && is_array($details['customizations'])) {
                    foreach ($details['customizations'] as $cost) $itemUnitPrice += (float) $cost;
                }

                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_id'     => $details['product_id'],
                    'product_name'   => $details['name'],
                    'quantity'       => $details['quantity'],
                    'price'          => $itemUnitPrice,
                    'size'           => $details['size'] ?? 'Standard',
                    'customizations' => $details['customizations'] ?? null,
                ]);

                Product::where('id', $details['product_id'])->decrement('stock_quantity', $details['quantity']);
            }

            if ($pointsRedeemed > 0) {
                $user->decrement('loyalty_points', $pointsRedeemed);
                PointTransaction::create([
                    'user_id'     => $user->id,
                    'amount'      => -$pointsRedeemed,
                    'description' => "Redemption: " . $rewardType,
                    'order_id'    => $order->id,
                ]);
            }

            $user->increment('loyalty_points', $pointsEarned);
            PointTransaction::create([
                'user_id'     => $user->id,
                'amount'      => $pointsEarned,
                'description' => "Yield: Order #{$order->id} ({$multiplier}x {$tier} tier)",
                'order_id'    => $order->id,
            ]);

            if ($user->orders()->count() % 10 === 0) {
                $user->increment('loyalty_points', 150);
                PointTransaction::create([
                    'user_id'     => $user->id,
                    'amount'      => 150,
                    'description' => "Punch Card Milestone Reached",
                    'order_id'    => $order->id,
                ]);
            }

            DB::commit();

            session()->forget(['cart', 'claimed_reward']);

            $order->load('items.product');
            try {
                Mail::to($user->email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {
                Log::error("Email Dispatch Failure: " . $e->getMessage());
            }

            return redirect()->route('checkout.receipt', $order->id)->with('success', 'Order finalized successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Commit Failure: " . $e->getMessage());
            return redirect()->back()->with('error', 'Critical Transaction Fault: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->usertype !== 'admin') abort(403);
        $orders   = Order::with(['user'])->latest()->get();
        $fileName = 'sales_' . date('Y-m-d') . '.csv';
        $headers  = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Customer', 'Amount', 'Date', 'Status']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Guest',
                    '₱' . number_format($order->total_price, 2),
                    $order->created_at->format('Y-m-d H:i'),
                    ucfirst($order->status),
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function downloadReceipt($id)
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        if ($order->user_id !== $user->id && $user->usertype !== 'admin') abort(403);
        $pts   = $user->loyalty_points ?? 0;
        $tier = $pts >= 500 ? 'Gold' : ($pts >= 200 ? 'Silver' : 'Bronze');
        return view('emails.order_receipt', compact('order', 'tier'));
    }

    public function transferPoints(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount'          => 'required|integer|min:5',
        ]);
        /** @var \App\Models\User $sender */
        $sender    = User::find(Auth::id());
        $recipient = User::where('email', $request->recipient_email)->first();
        if ($sender->id === $recipient->id) return back()->with('error', 'Operation Restricted.');
        if ($sender->loyalty_points < $request->amount) return back()->with('error', 'Insufficient assets.');
        DB::transaction(function () use ($sender, $recipient, $request) {
            $amount = (int) $request->amount;
            $sender->decrement('loyalty_points', $amount);
            $recipient->increment('loyalty_points', $amount);
            PointTransaction::create(['user_id' => $sender->id,    'amount' => -$amount, 'description' => "Transferred points to {$recipient->name}"]);
            PointTransaction::create(['user_id' => $recipient->id, 'amount' =>  $amount, 'description' => "Received points from {$sender->name}"]);
        });
        return back()->with('success', 'Asset transmission complete.');
    }
}