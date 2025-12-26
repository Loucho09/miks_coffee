<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Georgia', serif; background-color: #fafaf9; color: #1c1917; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border: 1px solid #e7e5e4; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #1c1917; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-style: italic; font-size: 24px; }
        .header span { color: #d97706; font-weight: 900; letter-spacing: 3px; font-size: 10px; text-transform: uppercase; font-family: sans-serif; }
        .content { padding: 40px; line-height: 1.6; }
        .subject-badge { display: inline-block; padding: 4px 12px; background: #fff7ed; color: #b45309; border-radius: 12px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .message-box { background-color: #f5f5f4; border-radius: 16px; padding: 20px; font-style: italic; border-left: 4px solid #d97706; margin: 20px 0; }
        .footer { padding: 30px; text-align: center; font-size: 10px; color: #a8a29e; text-transform: uppercase; letter-spacing: 1px; border-top: 1px solid #f5f5f4; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mik's</h1>
            <span>Coffee Shop Support</span>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>
            
            <div class="subject-badge">Re: {{ $ticketSubject }}</div>
            
            <p>Our team has reviewed your request. Here is our response:</p>
            
            <div class="message-box">
                "{{ $replyMessage }}"
            </div>
            
            <p>If you have any further questions, feel free to visit us or reply to this message.</p>
            
            <p>Best regards,<br><strong>The Mik's Coffee Team</strong></p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Mik's Coffee Shop • Freshly Brewed Support
        </div>
    </div>
</body>
</html>