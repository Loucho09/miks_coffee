<?php

namespace App\Http\Controllers\Barista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PointTransaction;
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

        $orders = Order::whereIn('status', ['pending', 'preparing', 'ready'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        $redemptions = PointTransaction::where('amount', '<', 0)
                        ->where('created_at', '>=', now()->subDay())
                        ->where('description', 'not like', '[FULFILLED]%')
                        ->with('user')
                        ->latest()
                        ->get();

        return view('barista.queue', compact('orders', 'redemptions'));
    }

    /**
     * Fetch Active Redemptions for Live Updates (No Refresh)
     */
    public function getActiveRedemptions()
    {
        if (Auth::user()->usertype !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(
            PointTransaction::where('amount', '<', 0)
                ->where('created_at', '>=', now()->subDay())
                ->where('description', 'not like', '[FULFILLED]%')
                ->with('user')
                ->latest()
                ->get()
        );
    }

    /**
     * Fetch Active Orders for Live Updates (No Refresh)
     */
    public function getActiveOrders()
    {
        if (Auth::user()->usertype !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $orders = Order::whereIn('status', ['pending', 'preparing', 'ready'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        return response()->json($orders);
    }

    /**
     * Fulfill a reward claim
     */
    public function fulfillRedemption($id)
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403);
        }

        $transaction = PointTransaction::findOrFail($id);
        
        $transaction->update([
            'description' => '[FULFILLED] ' . $transaction->description
        ]);

        return back()->with('success', 'Reward fulfilled and cleared from terminal.');
    }

    /**
     * Update Order Status
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->usertype !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $order = Order::with('user')->findOrFail($id);
        $request->validate(['status' => 'required|in:pending,preparing,ready,served,completed,cancelled']);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('barista.queue')->with('success', 'Order status updated.');
    }
}