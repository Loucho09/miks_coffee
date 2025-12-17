<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // 🟢 Needed to check if items exist
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderReceipt;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // 1. Show Order History
    public function index()
    {
        $orders = Order::with('items.product')->where('user_id', Auth::id())->latest()->get();
        return view('cafe.orders', compact('orders'));
    }

    // 2. Claim Reward Logic (Saves to session)
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

    // 3. Store Order (The Checkout Process)
    public function store(Request $request)
    {
        // A. Validation
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'payment_method' => 'required|in:cash,card',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('menu.index')->with('error', 'Your cart is empty!');
        }

        // 🟢 B. GHOST ITEM CHECK (CRITICAL FIX) 🟢
        // This prevents the "Foreign Key Constraint Failed" error
        foreach ($cart as $key => $details) {
            // Handle both old cart structure (key=id) and new structure (details['product_id'])
            $productId = $details['product_id'] ?? $key;
            
            $productExists = Product::where('id', $productId)->exists();

            if (!$productExists) {
                // Remove the "Ghost" item from the session
                unset($cart[$key]);
                session()->put('cart', $cart);
                
                return redirect()->route('cart.index')
                    ->with('error', 'One or more items in your cart are no longer available and have been removed.');
            }
        }

        // C. Calculate Totals
        $totalAmount = 0;
        foreach ($cart as $details) {
            $totalAmount += $details['price'] * $details['quantity'];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        // D. Calculate Rewards & Discounts
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
        } 
        elseif ($request->has('redeem_points') && $user->points >= 50) {
            $discount = 50;
            $pointsRedeemed = 50;
            $rewardType = 'Standard Discount';
        }

        // Prevent negative total
        if ($totalAmount < $discount) {
            $discount = $totalAmount;
        }

        $finalTotal = number_format((float)($totalAmount - $discount), 2, '.', '');

        // E. Database Transaction
        DB::beginTransaction();
        try {
            // 1. Create the Order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => $finalTotal,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'points_earned' => 10, // Standard points earned
                'points_redeemed' => $pointsRedeemed,
                'reward_type' => $rewardType,
                'notes' => $rewardType ? "Reward: $rewardType" : null,
            ]);

            // 2. Save Order Items
            foreach ($cart as $key => $details) {
                // Ensure we get the correct ID whether using simple or composite keys
                $productId = $details['product_id'] ?? $key;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                    // If you added a 'size' column to order_items, add it here:
                    // 'size' => $details['size'] ?? 'Regular', 
                ]);

                // 3. Deduct Stock
                $product = Product::find($productId);
                if ($product) {
                    $product->decrement('stock_quantity', $details['quantity']);
                }
            }

            // 4. Update User Points
            if ($pointsRedeemed > 0) {
                $user->decrement('points', $pointsRedeemed);
            }
            $user->increment('points', 10);
            $user->save();

            // 5. Commit Transaction
            DB::commit();

            // 6. Clear Session
            session()->forget('cart');
            session()->forget('claimed_reward');

            // 7. Send Email
            try {
                Mail::to($request->customer_email)->send(new OrderReceipt($order));
            } catch (\Exception $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            return redirect()->route('orders.index')->with('success', 'Order placed successfully! You earned 10 points.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Failed: ' . $e->getMessage());
            
            // Redirect back with a friendly error message
            return redirect()->route('cart.index')->with('error', 'Order failed. Please try again or clear your cart.');
        }
    }

    // 4. Download PDF
    public function downloadReceipt($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pdf = Pdf::loadView('pdf.receipt', compact('order'));
        return $pdf->download('Miks-Receipt-#' . $order->id . '.pdf');
    }
}