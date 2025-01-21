@extends('layouts.layout')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Riwayat Pemesanan Tiket</h1>

        @if ($orders->isEmpty())
            <p>Belum ada pemesanan tiket.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Konser</th>
                        <th>Lokasi</th>
                        <th>Tanggal Konser</th>
                        <th>Jenis Tiket</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Tanggal Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->concert->name }}</td>
                            <td>{{ $order->concert->location }}</td>
                            <td>{{ $order->concert->date->format('d-m-Y') }}</td>
                            <td>{{ ucfirst($order->ticket_type) }}</td>
                            <td>{{ $order->ticket_count }}</td>
                            <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
