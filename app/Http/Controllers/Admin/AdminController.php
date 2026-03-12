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
     * Optimized for Laravel Cloud persistence and instant feedback.
     */
    public function processScan(Request $request)
    {
        // 1. Validate Input
        $request->validate(['token' => 'required|string']);
        $token = $request->token;

        // 2. Locate Manifest
        $order = Order::where('qr_claim_token', $token)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Invalid Token - Order Not Found']);
        }

        if ($order->points_awarded) {
            return response()->json(['success' => false, 'message' => 'Stars already claimed for this order']);
        }

        try {
            // Force a higher time limit for cloud stability during DB transaction
            set_time_limit(20);

            // 3. Atomic Database Update
            // We use a retry count of 3 to handle potential deadlock/concurrency issues on cloud DBs
            DB::transaction(function () use ($order) {
                $user = User::findOrFail($order->user_id);
                
                // Atomic increment to prevent race conditions on shared cloud environments
                $user->increment('loyalty_points', 10);
                
                $order->update(['points_awarded' => true]);

                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => 10,
                    'type' => 'earned',
                    'description' => "Manifest #{$order->id} scanned at counter."
                ]);
            }, 3);

            $finalUser = User::find($order->user_id);

            return response()->json([
                'success' => true, 
                'message' => "COMPLETE",
                'customer' => $finalUser->name ?? 'Customer'
            ]);

        } catch (\Exception $e) {
            Log::error('Cloud Scan Fatal Failure: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Cloud Database Busy - Please Retry Scan'
            ], 500);
        }
    }
}