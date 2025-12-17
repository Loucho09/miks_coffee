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
            session()->flash('success', 'Order #' . $order->id . ' marked as ' . strtoupper($status));
        }
    }

    public function render()
    {
        // Matches your original QueueController logic perfectly
        $orders = Order::whereIn('status', ['pending', 'preparing'])
                        ->with(['items.product', 'user']) 
                        ->orderBy('id', 'asc')
                        ->get();

        return view('livewire.barista-queue', compact('orders'));
    }
}