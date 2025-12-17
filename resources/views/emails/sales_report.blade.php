<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px; }
        .container { background-color: #ffffff; padding: 30px; border-radius: 8px; max-width: 600px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #333; margin: 0; }
        .stat-box { background: #f9fafb; padding: 15px; border: 1px solid #ddd; margin-bottom: 10px; text-align: center; border-radius: 5px; }
        .stat-value { font-size: 24px; font-weight: bold; color: #059669; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 {{ $period }} Sales Report</h1>
        </div>

        <div class="stat-box">
            <p>Total Revenue</p>
            <div class="stat-value">₱{{ number_format($totalSales, 2) }}</div>
        </div>

        <div class="stat-box">
            <p>Total Orders</p>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Miks Coffee Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>