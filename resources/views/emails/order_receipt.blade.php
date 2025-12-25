<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #fafaf9; color: #1c1917; padding: 20px; }
        .receipt { max-width: 400px; margin: auto; background: white; padding: 30px; border-radius: 20px; border: 1px solid #e7e5e4; }
        .brand { text-align: center; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border-bottom: 2px dashed #f5f2ea; padding-bottom: 20px; }
        .tier-badge { display: block; text-align: center; margin: 15px 0; padding: 6px; background: #f5f2ea; color: #b45309; font-size: 10px; font-weight: 900; border-radius: 99px; text-transform: uppercase; }
        /* 🟢 CSS FIX: Replaced invalid #amber-600 with Hex code #d97706 */
        .milestone-alert { text-align: center; font-size: 10px; color: #d97706; font-weight: 800; margin-bottom: 15px; text-transform: uppercase; }
        .item-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 10px; }
        .total { border-top: 2px solid #1c1917; margin-top: 20px; padding-top: 15px; font-weight: 900; font-size: 18px; display: flex; justify-content: space-between; }
        .footer { text-align: center; font-size: 10px; color: #a8a29e; margin-top: 30px; }
        .btn { display: block; width: 100%; margin-top: 20px; padding: 12px; background: #1c1917; color: white; border: none; border-radius: 10px; font-weight: 800; cursor: pointer; text-transform: uppercase; }
        @media print { .btn { display: none; } .receipt { border: none; box-shadow: none; width: 100%; } }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="brand">Mik's Coffee</div>
        
        <span class="tier-badge">{{ $tier ?? 'Member' }} Status</span>

        @if(isset($diff) && $diff > 0 && $diff <= 50)
            <div class="milestone-alert">
                ✨ Almost there! {{ $diff }} points to your next tier ✨
            </div>
        @endif

        <div style="text-align: center; font-size: 11px; color: #78716c; margin-bottom: 20px;">
            Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}
        </div>

        @foreach($order->items as $item)
            <div class="item-row">
                <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
            </div>
        @endforeach

        <div class="total">
            <span>TOTAL DUE</span>
            <span>₱{{ number_format($order->total_price, 2) }}</span>
        </div>

        <div class="footer">
            <p>Thank you for choosing Mik's!</p>
        </div>

        <button onclick="window.print()" class="btn">Print Receipt</button>
    </div>
</body>
</html>