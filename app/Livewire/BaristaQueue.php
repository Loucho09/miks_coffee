<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class BaristaQueue extends Component
{
    public function updateStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order) {
            $order->update(['status' => $status]);
            
            // This flash message will show up on the page without a refresh
            session()->flash('success', 'Order #' . $order->id . ' is now ' . strtoupper($status));
        }
    }

    public function render()
    {
        // We fetch the orders here so that every time a button is clicked, 
        // Livewire re-runs this query and sees the new status.
        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        return view('livewire.barista-queue', compact('orders'));
    }
}