<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
            color: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .order-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .order-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .order-details li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details li:last-child {
            border-bottom: none;
        }
        .important-notes {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .important-notes ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4158D0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
        .button:hover {
            background-color: #3448a5;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎵 MUSICxASIX</h1>
        <p>Your Concert Tickets Are Here!</p>
    </div>

    <div class="content">
        <p>Dear {{ $order->user->name }},</p>

        <p>Thank you for your purchase! Your tickets for <strong>{{ $order->concert->name }}</strong> are attached to this email.</p>

        <div class="order-details">
            <h2>Order Details:</h2>
            <ul>
                <li><strong>Order ID:</strong> {{ $order->id }}</li>
                <li><strong>Concert:</strong> {{ $order->concert->name }}</li>
                <li><strong>Date:</strong> {{ $order->concert->date->format('l, d F Y') }}</li>
                <li><strong>Time:</strong> {{ $order->concert->date->format('H:i') }} WIB</li>
                <li><strong>Venue:</strong> {{ $order->concert->venue }}</li>
                <li><strong>Ticket Type:</strong> {{ strtoupper($order->ticket_type) }}</li>
                <li><strong>Number of Tickets:</strong> {{ $order->ticket_count }}</li>
            </ul>
        </div>

        <p>Your tickets are attached to this email in PDF format. You can either print them or show them on your mobile device at the entrance.</p>

        <div class="important-notes">
            <h3>Important Notes:</h3>
            <ul>
                <li>Each ticket has a unique QR code that will be scanned at entry</li>
                <li>Please arrive at least 30 minutes before the show</li>
                <li>Don't forget to bring a valid ID</li>
                <li>Each ticket can only be used once</li>
                <li>Keep your tickets safe and don't share them with others</li>
            </ul>
        </div>

        <p>You can also view your tickets anytime by logging into your account:</p>
        <center>
            <a href="{{ route('tickets.index') }}" class="button">View My Tickets</a>
        </center>

        <div class="footer">
            <p>If you have any questions, please don't hesitate to contact our support team.</p>
            <p>Enjoy the show!</p>
            <p>Best regards,<br>MUSICxASIX Team</p>
        </div>
    </div>
</body>
</html>
