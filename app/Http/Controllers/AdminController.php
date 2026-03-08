<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * AJAX Endpoint for QR Scanner
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
                $user = User::findOrFail($order->user_id);
                
                // Award stars (10 by default or order specific)
                $user->increment('loyalty_points', 10);
                
                // Lock the manifest to prevent double-claiming
                $order->update(['points_awarded' => true]);

                // Log the transaction for the admin activity feed
                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'points' => 10,
                    'type' => 'earned',
                    'description' => "Manifest #{$order->id} scanned at counter."
                ]);
            });

            return response()->json([
                'success' => true, 
                'message' => "Success! +10 Stars added to {$order->user->name}.",
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Operational Failure: Please try again.']);
        }
    }
}