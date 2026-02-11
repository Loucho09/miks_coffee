<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Account</title>
</head>
<body style="margin:0; padding:0; background-color: #F5F2EA; font-family: sans-serif;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#F5F2EA">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#1A1816" style="border-radius: 40px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2);">
                    <tr>
                        <td align="center" style="padding: 40px 0 20px 0;">
                            <h1 style="color: #FFFFFF; font-style: italic; font-size: 28px; margin: 0;">Mik's <span style="color: #D97706; font-style: normal; font-weight: bold;">COFFEE</span></h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 50px 40px 50px; text-align: center;">
                            <h2 style="color: #FFFFFF; font-size: 22px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Verification Code</h2>
                            <p style="color: #B8AD91; font-size: 16px; line-height: 24px;">Hello {{ $user->name }},<br>Welcome to the culture! Use the code below to verify your account and start grabbing deals.</p>
                            
                            <div style="background: #0C0B0A; padding: 30px; border-radius: 25px; margin: 30px 0; border: 1px solid #3B352A;">
                                <span style="color: #F59E0B; font-size: 48px; font-weight: 900; letter-spacing: 15px;">{{ $code }}</span>
                            </div>

                            <p style="color: #736852; font-size: 12px; font-style: italic;">This code will expire soon. If you didn't request this, please ignore this email.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 30px; background: #0C0B0A; border-top: 1px solid #3B352A;">
                            <p style="color: #574F3E; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin: 0;">© 2026 Mik's Coffee Shop • Trece Martires</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>