<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemesanan</title>
</head>
<body>
    <h1>Terima Kasih! Pemesanan Anda Telah Berhasil</h1>

    <p>Nama Konser: {{ $order->concert_name }}</p>
    <p>Jumlah Tiket: {{ $order->ticket_count }}</p>
    <p>Total Harga: Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
    <p>Status: {{ $order->status ?? 'Pending' }}</p>

    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
</body>
</html>
