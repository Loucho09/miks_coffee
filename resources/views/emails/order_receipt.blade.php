<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Manifest #{{ $order->order_number }}</title>
    <style>
        body { 
            font-family: 'Courier New', Courier, monospace; 
            background-color: #fafaf9; 
            color: #1c1917; 
            padding: 40px 20px; 
            margin: 0;
        }
        .receipt { 
            max-width: 450px; 
            margin: auto; 
            background: #ffffff; 
            padding: 40px; 
            border-radius: 24px; 
            border: 1px solid #e7e5e4; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .brand { 
            text-align: center; 
            font-size: 28px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 4px; 
            border-bottom: 2px dashed #f5f2ea; 
            padding-bottom: 25px; 
            margin-bottom: 25px;
        }
        .tier-badge { 
            display: block; 
            text-align: center; 
            margin: 0 auto 20px auto; 
            padding: 8px 16px; 
            background-color: #f5f2ea; 
            color: #b45309; 
            font-size: 11px; 
            font-weight: 900; 
            border-radius: 99px; 
            text-transform: uppercase;
            width: fit-content;
        }
        .meta-row { 
            text-align: center; 
            font-size: 11px; 
            color: #78716c; 
            margin-bottom: 30px; 
            text-transform: uppercase; 
            line-height: 1.8;
            letter-spacing: 1px;
        }
        .item-row { 
            display: flex; 
            justify-content: space-between; 
            font-size: 13px; 
            margin-bottom: 4px; 
            font-weight: 600;
        }
        .addon-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #78716c;
            margin-bottom: 2px;
            padding-left: 20px;
            text-transform: uppercase;
            font-style: italic;
        }
        .subtotal-row { 
            display: flex; 
            justify-content: space-between; 
            font-size: 11px; 
            margin-top: 20px; 
            padding-top: 15px; 
            border-top: 1px dashed #e7e5e4; 
            font-weight: 700; 
            color: #78716c; 
            text-transform: uppercase;
        }
        .discount { 
            font-size: 11px; 
            color: #10b981; 
            display: flex; 
            justify-content: space-between; 
            margin-top: 10px; 
            text-transform: uppercase; 
            font-weight: 900; 
        }
        .total { 
            border-top: 2px solid #1c1917; 
            margin-top: 15px; 
            padding-top: 20px; 
            font-weight: 900; 
            font-size: 22px; 
            display: flex; 
            justify-content: space-between; 
            font-style: italic;
        }
        .points-row { 
            text-align: center; 
            margin-top: 25px; 
            font-size: 10px; 
            font-weight: 800; 
            color: #b45309; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer { 
            text-align: center; 
            font-size: 10px; 
            color: #a8a29e; 
            margin-top: 40px; 
            border-top: 1px solid #f5f2ea;
            padding-top: 20px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="brand">Mik's Coffee</div>

        <span class="tier-badge">Member Status Authorized</span>

        <div class="meta-row">
            Manifest ID: #{{ $order->order_number }}<br>
            Authorized: {{ $order->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
        </div>

        @php
            $gross = 0;
        @endphp

        @foreach($order->items as $item)
            @php 
                $itemBaseTotal = (float)$item->price * $item->quantity;
                $gross += $itemBaseTotal;
            @endphp
            <div class="item-container" style="margin-bottom: 15px;">
                <div class="item-row">
                    <span>{{ $item->quantity }}x {{ $item->product_name }} [{{ $item->size }}]</span>
                    <span>PHP {{ number_format($itemBaseTotal, 2) }}</span>
                </div>

                @if(!empty($item->customizations) && is_array($item->customizations))
                    @foreach($item->customizations as $label => $price)
                        <div class="addon-row">
                            <span>+ {{ $label }}</span>
                            <span>PHP {{ number_format((float)$price, 2) }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach

        @if($order->points_redeemed > 0)
            @php $discountApplied = max(0, $gross - (float) $order->total_price); @endphp

            <div class="subtotal-row">
                <span>Subtotal</span>
                <span>PHP {{ number_format($gross, 2) }}</span>
            </div>
            <div class="discount">
                <span>Power Discount ({{ $order->reward_type }})</span>
                <span>-PHP {{ number_format($discountApplied, 2) }}</span>
            </div>
        @endif

        <div class="total">
            <span>NET TOTAL</span>
            <span>PHP {{ number_format((float) $order->total_price, 2) }}</span>
        </div>

        <div class="points-row">
            Yield Applied: +{{ $order->points_earned }} Star Points
            @if($order->points_redeemed > 0)
                &nbsp;·&nbsp; Redeemed: -{{ $order->points_redeemed }} PTS
            @endif
        </div>

        <div class="footer">
            <p>Thank you for choosing Mik's Coffee</p>
            <p>Official Order Manifest</p>
        </div>
    </div>
</body>
</html>