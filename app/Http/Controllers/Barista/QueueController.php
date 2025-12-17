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
        // 1. Security Check
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized. Admins/Baristas only.');
        }

        // 2. Fetch Active Orders
        // Optimized with 'with()' to load relationships efficiently
        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc') // FIFO
                        ->get();

        return view('barista.queue', compact('orders'));
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

        // 🌟 LOYALTY POINTS LOGIC 🌟
        // If order becomes 'ready' and hasn't been rewarded yet:
        if ($request->status === 'ready' && $order->status !== 'ready' && $order->user) {
            // Logic: 1 Point for every 50 Pesos spent
            $pointsEarned = floor($order->total_amount / 50);
            
            // Add points to customer account
            $order->user->increment('points', $pointsEarned);
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->route('barista.queue')
            ->with('success', 'Order #' . $order->id . ' status updated to ' . strtoupper($request->status));
    }
}