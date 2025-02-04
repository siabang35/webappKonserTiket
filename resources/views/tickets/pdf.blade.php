<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Concert Tickets</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .ticket-container {
            padding: 20px;
        }
        .ticket {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #000;
            border-radius: 15px;
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            page-break-inside: avoid;
            position: relative;
            overflow: hidden;
        }
        .ticket::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIiB3aWR0aD0iMzAiIGhlaWdodD0iMzAiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxsaW5lIHgxPSIwIiB5MT0iMCIgeDI9IjAiIHkyPSIzMCIgc3Ryb2tlPSIjZjBmMGYwIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz48L3N2Zz4=') repeat;
            opacity: 0.1;
        }
        .ticket-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        .ticket-header h1 {
            color: #1a1a1a;
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .ticket-header h2 {
            color: #4a4a4a;
            font-size: 18px;
            margin: 5px 0;
        }
        .ticket-details {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
        }
        .ticket-info {
            flex: 1;
            padding: 0 15px;
        }
        .ticket-info h3 {
            color: #666;
            font-size: 14px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .ticket-info p {
            color: #333;
            font-size: 16px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        .ticket-code {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .ticket-code span {
            font-family: monospace;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .serial-number {
            text-align: center;
            font-family: monospace;
            font-size: 14px;
            color: #666;
            margin: 10px 0;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
        .ticket-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #ccc;
            font-size: 12px;
            color: #666;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.03);
            white-space: nowrap;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        @foreach($tickets as $ticket)
        <div class="ticket">
            <div class="watermark">MUSICxASIX</div>

            <div class="ticket-header">
                <h1>{{ $concert->name }}</h1>
                <h2>{{ strtoupper($order->ticket_type) }} TICKET</h2>
            </div>

            <div class="serial-number">
                Serial Number: {{ $ticket->serial_number }}
            </div>

            <div class="ticket-details">
                <div class="ticket-info">
                    <h3>Date & Time</h3>
                    <p>{{ $concert->date->format('l, d F Y') }}</p>
                    <p>{{ $concert->date->format('H:i') }} WIB</p>
                </div>
                <div class="ticket-info">
                    <h3>Venue</h3>
                    <p>{{ $concert->venue }}</p>
                </div>
                <div class="ticket-info">
                    <h3>Ticket Holder</h3>
                    <p>{{ $order->user->name }}</p>
                    <p class="text-sm">{{ $order->user->email }}</p>
                </div>
            </div>

            <div class="ticket-code">
                <h3>Ticket Code</h3>
                <span>{{ $ticket->ticket_code }}</span>
            </div>

            <div class="qr-code">
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(json_decode(base64_decode($ticket->qr_code), true)) !!}
            </div>

            <div class="ticket-footer">
                <p>Valid until {{ $ticket->valid_until->format('d F Y H:i') }} WIB</p>
                <p class="ticket-notice">This ticket is valid for one-time entry only. Unauthorized duplication is prohibited.</p>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>
