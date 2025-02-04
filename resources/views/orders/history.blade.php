@extends('layouts.layout')

@section('title', 'Riwayat Pemesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card Riwayat Pemesanan -->
            <div class="card shadow" style="border-radius: 1rem; overflow: hidden;">
                <!-- Header dengan Gradasi -->
                <div class="card-header" style="background: linear-gradient(135deg, rgba(65, 88, 208, 0.8), rgba(200, 80, 192, 0.8)); color: white; padding: 1.5rem;">
                    <h3 class="mb-0 text-center" style="font-weight: bold; text-transform: uppercase;">Riwayat Pemesanan Tiket</h3>
                </div>
                <!-- Konten Card -->
                <div class="card-body" style="background: rgba(255, 255, 255, 0.9);">
                    <!-- Pesan jika tidak ada pemesanan -->
                    @if ($orders->isEmpty())
                        <p class="text-center" style="font-weight: bold; color: #6c757d;">Belum ada pemesanan tiket.</p>
                    @else
                        <!-- Tabel Riwayat Pemesanan -->
                        <table class="table table-striped" style="border-radius: 0.5rem; overflow: hidden;">
                            <thead style="background: linear-gradient(135deg, rgba(65, 88, 208, 0.8), rgba(200, 80, 192, 0.8)); color: white;">
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
        </div>
    </div>
</div>
@endsection
