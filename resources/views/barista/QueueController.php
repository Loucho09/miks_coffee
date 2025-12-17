<?php

namespace App\Http\Controllers\Barista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; // <--- This was missing
use App\Models\Order;

class QueueController extends Controller
{
    public function index()
    {
        // Fetch orders that are strictly 'pending' or 'preparing'
        // We use 'with' to load the items and user info efficiently (prevents lag)
        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc') // FIFO: Oldest orders first
                        ->get();
                        
        return view('barista.queue', compact('orders'));
    }
    
    public function updateStatus($id, Request $request)
    {
        $order = Order::findOrFail($id);
        
        // Validate that the status is a valid workflow step
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,cancelled'
        ]);

        // Update the status
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->route('barista.queue')
            ->with('success', 'Order #' . $order->id . ' status updated to ' . strtoupper($request->status));
    }
}