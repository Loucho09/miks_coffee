<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class BaristaQueue extends Component
{
    public function render()
    {
        // Re-executing the query within render ensures the list is fresh on every lifecycle.
        // Orders with status 'ready' are automatically excluded.
        $orders = Order::whereIn('status', ['pending', 'preparing'])
            ->with(['items.product', 'user'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.barista-queue', [
            'orders' => $orders
        ]);
    }

    public function updateStatus($orderId, $newStatus)
    {
        if (Auth::user()->usertype !== 'admin') return;

        $order = Order::with('user')->findOrFail($orderId);

        // Loyalty Points Logic
        if ($newStatus === 'ready' && $order->status !== 'ready' && $order->user) {
            $amount = $order->total_amount ?? $order->total_price ?? 0;
            $order->user->increment('points', floor($amount / 50));
        }

        $order->status = $newStatus;
        $order->save(); // Database persistence

        session()->flash('success', "Order #$orderId updated to $newStatus");
    }
}