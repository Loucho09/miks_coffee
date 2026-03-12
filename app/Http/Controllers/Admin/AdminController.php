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
            // 3. Define user before transaction to ensure scope availability for the response
            $user = User::findOrFail($order->user_id);

            // 4. Atomic Database Update
            DB::transaction(function () use ($order, $user) {
                // Explicit save to bypass attribute caching glitches on cloud environments
                $currentPoints = (int)($user->loyalty_points ?? 0);
                $user->loyalty_points = $currentPoints + 10;
                $user->save(); 
                
                $order->points_awarded = true;
                $order->save();

                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'amount' => 10,
                    'type' => 'earned',
                    'description' => "Manifest #{$order->id} scanned at counter."
                ]);
            });

            return response()->json([
                'success' => true, 
                'message' => "COMPLETE",
                'customer' => $user->name ?? 'Customer'
            ]);

        } catch (\Exception $e) {
            Log::error('Cloud Scan Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Operational Failure - Database Busy']);
        }
    }
}