<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h1>Thank you for your order! ☕</h1>
    <p>Hi {{ $order->customer_name }},</p>
    <p>Your order <strong>#{{ $order->id }}</strong> has been received and is being prepared.</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr style="background-color: #f3f3f3;">
            <th style="padding: 10px; text-align: left;">Item</th>
            <th style="padding: 10px; text-align: right;">Qty</th>
            <th style="padding: 10px; text-align: right;">Price</th>
        </tr>
        @foreach ($order->items as $item)
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $item->product_name }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">{{ $item->quantity }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd; text-align: right;">₱{{ number_format($item->price, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold;">Total:</td>
            <td style="padding: 10px; text-align: right; font-weight: bold;">₱{{ number_format($order->total_amount, 2) }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">Visit the shop to track your status!</p>
</body>
</html>