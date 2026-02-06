<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Handle the sales data export.
     * 🟢 FIXED via Alternative Way: Dedicated Controller logic.
     */
    public function __invoke(): StreamedResponse
    {
        /** @var User $user */
        $user = Auth::user();

        // Security: Check if user has admin rights
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $orders = Order::with(['user', 'items'])->latest()->get();
        $fileName = 'miks_coffee_sales_' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return response()->stream(function () use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Order ID', 'Customer', 'Amount (₱) ', 'Status', 'Date', 'Order Type']);

            foreach ($orders as $order) {
                // 🟢 NEW FEATURE: Auto-Categorization based on item count
                $itemCount = $order->items->sum('quantity');
                $orderType = ($itemCount >= 5) ? 'Large Group Order' : 'Standard Order';

                fputcsv($file, [
                    $order->id,
                    $order->user->name ?? 'Guest',
                    number_format($order->total_price, 2, '.', ''),
                    ucfirst($order->status),
                    $order->created_at->format('Y-m-d H:i'),
                    $orderType
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }
}