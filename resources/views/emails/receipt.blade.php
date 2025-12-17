<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; background: #f9f9f9; padding: 20px; }
        .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; border: 1px solid #ddd; }
        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #8D5F46; margin: 0; }
        .info { margin-bottom: 20px; line-height: 1.6; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { text-align: left; background: #eee; padding: 10px; }
        .table td { padding: 10px; border-bottom: 1px solid #eee; }
        
        /* POINTS HIGHLIGHTS */
        .points-box { background: #d1fae5; color: #065f46; padding: 15px; text-align: center; font-weight: bold; border-radius: 5px; margin-top: 20px; }
        .redeemed-text { color: #dc2626; font-weight: bold; }
        .total-text { font-size: 18px; font-weight: bold; margin-top: 10px; border-top: 1px solid #eee; padding-top: 10px; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>☕ Miks Coffee Shop</h1>
            <p>Thank you for your order!</p>
        </div>

        <div class="info">
            <strong>Order ID:</strong> #{{ $order->id }}<br>
            <strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name ?? $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right;">
            @if($order->points_redeemed > 0)
                <p class="redeemed-text">
                    Points Redeemed: -{{ $order->points_redeemed }} pts
                </p>
            @endif

            <div class="total-text">
                Total Paid: ₱{{ number_format($order->total_price, 2) }}
            </div>
        </div>

        @if($order->points_earned > 0)
            <div class="points-box">
                🎉 You earned +{{ $order->points_earned }} Points!
            </div>
        @endif
        
        <p style="text-align: center; color: #888; font-size: 12px; margin-top: 30px;">
            This is an automated receipt.
        </p>
    </div>
</body>
</html>