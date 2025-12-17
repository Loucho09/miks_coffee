<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #555; }
        .info { margin-bottom: 20px; background: #f9f9f9; padding: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { text-align: left; background: #eee; padding: 8px; border-bottom: 1px solid #ddd; }
        .table td { padding: 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        .total { text-align: right; font-weight: bold; font-size: 18px; margin-top: 10px; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 50px; }
        .size-badge { font-size: 10px; color: #666; font-weight: bold; text-transform: uppercase; }
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
            <strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}<br>
            <strong>Customer:</strong> {{ $order->customer_name ?? $order->user->name ?? 'Guest' }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th width="50%">Item</th>
                    <th width="15%" style="text-align: center;">Qty</th>
                    <th width="35%" style="text-align: right;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name ?? $item->product->name }}
                        
                        @if(!empty($item->size) && $item->size !== 'Regular')
                            <br><span class="size-badge">Size: {{ $item->size }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₱{{ number_format($item->price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            Total: ₱{{ number_format($order->total_price ?? $order->total_amount, 2) }}
        </div>

        <div class="footer">
            <p>Thank you for your purchase!</p>
            <p>Generated on {{ date('Y-m-d') }}</p>
        </div>
    </div>
</body>
</html>