<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #FDFBF7; color: #38322D; padding: 40px; }
        .container { background-color: #ffffff; padding: 40px; border-radius: 30px; border: 1px solid #E9E2DB; max-width: 600px; margin: 0 auto; }
        .header { border-bottom: 2px solid #d97706; padding-bottom: 20px; margin-bottom: 30px; }
        .label { font-weight: bold; color: #d97706; text-transform: uppercase; font-size: 10px; letter-spacing: 2px; }
        .content { font-size: 16px; line-height: 1.6; }
        .footer { margin-top: 30px; font-size: 12px; color: #7B6F63; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin:0; font-style: italic;">Mik's Coffee Support Request</h2>
        </div>
        <div class="content">
            <p><span class="label">Customer Name:</span><br> {{ $name }}</p>
            <p><span class="label">Customer Email:</span><br> {{ $email }}</p>
            <p><span class="label">Subject:</span><br> {{ $subject }}</p>
            <hr style="border: 0; border-top: 1px solid #E9E2DB; margin: 20px 0;">
            <p><span class="label">Message:</span><br> {{ $customerMessage }}</p>
        </div>
        <div class="footer">
            © 2025 Mik's Coffee Shop - Trece Martires
        </div>
    </div>
</body>
</html>