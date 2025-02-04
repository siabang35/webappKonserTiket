@extends('layouts.layout')

@section('title', 'Detail Konser')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-music-note-beamed me-2"></i>Detail Konser
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h3>{{ $concert->name }}</h3>
                            <p><strong>Lokasi:</strong> {{ $concert->location }}</p>
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }}</p>
                            <p><strong>Harga Tiket:</strong> Rp {{ number_format($concert->price, 2, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Deskripsi</h5>
                            <p>Deskripsi tentang konser bisa ditambahkan di sini. Misalnya, informasi tentang artis, genre musik, dan lainnya.</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('order.store') }}" class="btn btn-primary">
                            <i class="bi bi-ticket-perforated me-2"></i>Pemesanan Tiket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
