<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>

        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }
        .header h1 { color: #8D5F46; margin: 0; }
        .info { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { text-align: left; background: #f4f4f4; padding: 8px; border-bottom: 2px solid #ddd; }
        .table td { padding: 8px; border-bottom: 1px solid #eee; }
        .total-section { text-align: right; font-size: 16px; font-weight: bold; margin-top: 10px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>☕ Miks Coffee Shop</h1>
            <p>Order Receipt</p>
        </div>

        <div class="info">
            <strong>Order ID:</strong> #{{ $order->id }}<br>
            <strong>Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
            <strong>Customer:</strong> {{ $order->user->name }}
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
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            Total Paid: ₱{{ number_format($order->total_price ?? $order->total_amount, 2) }}
        </div>

        <div class="footer">
            Thank you for your business!
        </div>
    </div>
</body>
</html>