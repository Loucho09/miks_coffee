<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #0c0a09; font-family: 'Inter', sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0c0a09; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="600" style="max-width: 600px; background-color: #1c1917; border-radius: 40px; overflow: hidden; border: 1px solid #292524; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    
                    <tr>
                        <td align="center" style="padding: 40px 40px 20px 40px;">
                            <div style="background-color: rgba(245, 158, 11, 0.1); display: inline-block; padding: 8px 16px; border-radius: 100px; border: 1px solid rgba(245, 158, 11, 0.2);">
                                <span style="color: #f59e0b; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px;">Inventory Command</span>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 40px;">
                            <h1 style="color: #ffffff; font-size: 32px; font-weight: 900; margin: 10px 0; text-transform: uppercase; letter-spacing: -1px; font-style: italic;">
                                Stock Level Critical
                            </h1>
                            <p style="color: #a8a29e; font-size: 16px; margin: 0; font-weight: 400;">
                                Immediate restock required for:
                            </p>
                            <h2 style="color: #f59e0b; font-size: 24px; font-weight: 800; margin: 20px 0; text-transform: uppercase;">
                                {{ $product->name }}
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 20px 40px;">
                            <div style="background-color: #0c0a09; border-radius: 30px; padding: 40px; border: 2px dashed #ef4444; display: inline-block; min-width: 200px;">
                                <div style="color: #ef4444; font-size: 64px; font-weight: 900; line-height: 1;">
                                    {{ $product->stock_quantity }}
                                </div>
                                <div style="color: #78716c; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-top: 10px;">
                                    Units Remaining
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 40px;">
                            <p style="color: #78716c; font-size: 13px; line-height: 1.6; margin-bottom: 30px; max-width: 400px;">
                                This item has dropped below your threshold of 5 units. Check your supplier or update stock levels to avoid missed sales.
                            </p>
                            <a href="{{ route('admin.stock.index') }}" style="background-color: #f59e0b; color: #0c0a09; padding: 18px 36px; border-radius: 20px; text-decoration: none; font-weight: 900; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">
                                Open Stock Manager
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 40px 40px 40px;">
                            <div style="border-top: 1px solid #292524; padding-top: 30px;">
                                <p style="color: #44403c; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0;">
                                    Miks Coffee Shop &bull; Automated Monitoring System
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>