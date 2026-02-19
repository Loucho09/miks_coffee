<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #1c1917; background: #f9f9f9; padding: 20px; }
        .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 12px; border: 1px solid #e7e5e4; }
        .header { text-align: center; border-bottom: 3px solid #d97706; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { color: #1c1917; margin: 0; text-transform: uppercase; font-style: italic; }
        .info { margin-bottom: 20px; line-height: 1.6; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { text-align: left; background: #f5f5f4; padding: 12px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; }
        .table td { padding: 12px; border-bottom: 1px solid #f5f5f4; vertical-align: top; }
        .promo-tag { color: #d97706; font-size: 9px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-top: 4px; }
        .addon-item { font-size: 11px; color: #78716c; font-style: italic; }
        .points-box { background: #fef3c7; color: #92400e; padding: 15px; text-align: center; font-weight: bold; border-radius: 8px; margin-top: 20px; text-transform: uppercase; font-size: 11px; letter-spacing: 0.1em; }
        .total-text { font-size: 20px; font-weight: 900; margin-top: 10px; text-align: right; color: #1c1917; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>MIKS COFFEE</h1>
            <p style="text-transform: uppercase; font-weight: 800; font-size: 10px; color: #78716c;">Official Order Manifest</p>
        </div>

        <div class="info">
            <strong>ID:</strong> {{ $order->order_number }}<br>
            <strong>Timestamp:</strong> {{ $order->created_at->format('F d, Y h:i A') }}<br>
            <strong>Operator:</strong> {{ Auth::user()->name }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item Specification</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <span style="font-weight: 800; text-transform: uppercase;">{{ $item->product_name }}</span>
                        @if($item->size)<span style="font-size: 10px; color: #78716c;"> [{{ $item->size }}]</span>@endif
                        
                        {{-- 🟢 Golden Hour Check --}}
                        @php
                            $isGoldenHour = false;
                            if($item->product) {
                                $isGoldenHour = $item->product->is_happy_hour_active && (float)$item->price <= (float)$item->product->happy_hour_price;
                            }
                        @endphp
                        
                        @if($isGoldenHour)
                            <span class="promo-tag">✦ Golden Hour Promo Applied</span>
                        @endif

                        @if($item->customizations)
                            @foreach($item->customizations as $label => $price)
                                <div class="addon-item">+ {{ $label }}</div>
                            @endforeach
                        @endif
                    </td>
                    <td style="font-weight: 800;">{{ $item->quantity }}</td>
                    <td style="font-weight: 800; text-align: right;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right;">
            <div class="total-text">
                NET TOTAL: ₱{{ number_format($order->total_price, 2) }}
            </div>
        </div>

        <div class="points-box">
            Loyalty Assets updated: +10 Points Acquired
        </div>
        
        <p style="text-align: center; color: #a8a29e; font-size: 10px; margin-top: 30px; text-transform: uppercase; font-weight: 800;">
            Transaction Finalized at Trece Martires City
        </p>
    </div>
</body>
</html>