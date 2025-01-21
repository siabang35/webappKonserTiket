<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pemesanan</title>
</head>
<body>
    <h1>Ringkasan Pemesanan</h1>

    <p>Nama Konser: {{ $order->concert_name }}</p>
    <p>Jumlah Tiket: {{ $order->ticket_count }}</p>
    <p>Total Harga: Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>

    <form method="POST" action="{{ route('order.confirm') }}">
        @csrf
        <button type="submit">Konfirmasi Pemesanan</button>
    </form>

    <a href="{{ route('dashboard') }}">Batalkan</a>
</body>
</html>
