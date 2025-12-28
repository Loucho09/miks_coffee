<?php

namespace App\Http\Controllers\Barista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    /**
     * Show the Kitchen/Barista Queue
     */
    public function index()
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized. Admins/Baristas only.');
        }

        // Standard load for initial page hit
        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        return view('barista.queue', compact('orders'));
    }

    /**
     * 🟢 NEW FEATURE: Fetch Active Orders for Live Updates
     * Returns raw JSON for the Alpine.js frontend.
     */
    public function getActiveOrders()
    {
        if (Auth::user()->usertype !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        return response()->json($orders);
    }

    /**
     * Update Order Status & Award Points
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $order = Order::with('user')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,cancelled'
        ]);

        if ($request->status === 'ready' && $order->status !== 'ready' && $order->user) {
            $pointsEarned = floor($order->total_price / 50); // Standardized to total_price
            $order->user->increment('points', $pointsEarned);
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->route('barista.queue')
            ->with('success', 'Order #' . $order->id . ' status updated to ' . strtoupper($request->status));
    }
}