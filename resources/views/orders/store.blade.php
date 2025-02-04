@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg p-4 text-center">
        <h2 class="text-success">Pesanan Berhasil!</h2>
        <p class="mt-3">Terima kasih telah melakukan pemesanan tiket konser. Berikut adalah detail pesanan Anda:</p>

        <!-- Order Details -->
        <div class="mt-4">
            <h5>Detail Pesanan:</h5>
            <ul class="list-group">
                <li class="list-group-item"><strong>Nama Konser:</strong> {{ $order->concert->name }}</li>
                <li class="list-group-item"><strong>Jenis Tiket:</strong> {{ ucfirst($order->ticket_type) }}</li>
                <li class="list-group-item"><strong>Jumlah Tiket:</strong> {{ $order->ticket_count }}</li>
                <li class="list-group-item"><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</li>
            </ul>
        </div>

        <!-- Confirm or Back Buttons -->
        <div class="mt-4">
            <a href="{{ route('order.history') }}" class="btn btn-primary">Lihat Riwayat Pesanan</a>
            <a href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
