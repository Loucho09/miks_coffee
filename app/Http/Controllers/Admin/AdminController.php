<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * AJAX Endpoint for QR Scanner
     * Optimized for high-speed verification and guaranteed database persistence.
     */
    public function processScan(Request $request)
    {
        $request->validate(['token' => 'required|string']);
        
        // Find the manifest linked to the secure token
        $order = Order::where('qr_claim_token', $request->token)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Invalid Manifest: Token not recognized.']);
        }

        if ($order->points_awarded) {
            return response()->json(['success' => false, 'message' => 'Security Error: Stars already claimed for this order.']);
        }

        try {
            DB::transaction(function () use ($order) {
                /** @var User $user */
                $user = User::findOrFail($order->user_id);
                
                // FIXED: Manual increment and save to ensure persistence on Cloud DB
                $currentPoints = (int)($user->loyalty_points ?? 0);
                $user->loyalty_points = $currentPoints + 10;
                $user->save(); 
                
                // Lock the manifest to prevent double-claiming
                $order->points_awarded = true;
                $order->save();

                // Log the transaction for the admin activity feed
                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => 10,
                    'type' => 'earned',
                    'description' => "Manifest #{$order->id} scanned via Camera."
                ]);
            });

            return response()->json([
                'success' => true, 
                'message' => "COMPLETE", // Triggers the green UI state
                'customer' => $order->user->name ?? 'Customer',
                'new_total' => User::find($order->user_id)->loyalty_points
            ]);

        } catch (\Exception $e) {
            Log::error('Scan Processing Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Operational Failure: Points not added.']);
        }
    }
}