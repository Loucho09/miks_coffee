<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Base Styles */
        body {
            background-color: #0c0a09; /* stone-950 */
            color: #e7e5e4; /* stone-200 */
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0c0a09;
            padding-bottom: 40px;
        }
        .main {
            background-color: #1c1917; /* stone-900 */
            margin: 40px auto;
            width: 100%;
            max-width: 600px;
            border: 1px solid #292524; /* stone-800 */
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        /* Header Logic */
        .header {
            padding: 40px;
            text-align: center;
            border-bottom: 1px solid #292524;
        }
        .logo-circle {
            width: 60px;
            height: 60px;
            background-color: #ffffff;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: block;
        }
        .brand-name {
            font-family: Georgia, serif;
            font-style: italic;
            font-size: 24px;
            color: #fafaf9;
            margin: 0;
        }
        .brand-sub {
            font-weight: 900;
            font-size: 10px;
            letter-spacing: 0.3em;
            color: #d97706; /* amber-600 */
            text-transform: uppercase;
        }
        /* Content Area */
        .content {
            padding: 40px;
        }
        .status-badge {
            display: inline-block;
            background-color: rgba(217, 119, 6, 0.1);
            color: #d97706;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            padding: 5px 12px;
            border-radius: 20px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 22px;
            font-weight: 900;
            color: #fafaf9;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin: 0 0 10px;
        }
        .subject-line {
            font-size: 12px;
            color: #78716c; /* stone-500 */
            margin-bottom: 30px;
            font-style: italic;
        }
        .message-box {
            background-color: #0c0a09;
            border-radius: 24px;
            padding: 30px;
            border: 1px solid #292524;
        }
        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #d6d3d1;
            font-style: italic;
        }
        /* Footer */
        .footer {
            padding: 30px 40px;
            text-align: center;
            font-size: 10px;
            color: #57534e;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <div class="brand-name">Mik's</div>
                <div class="brand-sub">Coffee Terminal</div>
            </div>

            <div class="content">
                <div class="status-badge">Concierge Response</div>
                <h2>Hello, {{ $name }}</h2>
                <p class="subject-line">Regarding: {{ $ticketSubject }}</p>
                
                <div class="message-box">
                    <p class="message-text">
                        "{{ $replyMessage }}"
                    </p>
                </div>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} Mik's Coffee Shop &bull; Command Center Authorized
            </div>
        </div>
    </div>
</body>
</html>