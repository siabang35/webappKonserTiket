@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 text-center">
        <h1>Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="lead">Kelola tiket konser dengan mudah!</p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Pemesanan</h5>
                <p class="card-text">Jumlah tiket yang telah Anda pesan.</p>
                <a href="{{ route('order.history') }}" class="btn btn-primary">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Riwayat Pembelian</h5>
                <p class="card-text">Cek riwayat tiket konser Anda.</p>
                <a href="{{ route('order.history') }}" class="btn btn-primary">Lihat Riwayat</a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <h2 class="text-center mb-4">Daftar Konser</h2>
        <div class="row">
            @foreach($concerts as $concert)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100">
                        <img src="{{ $concert->image_url ?? 'https://via.placeholder.com/300x200' }}" class="card-img-top" alt="Poster {{ $concert->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $concert->name }}</h5>
                            <p class="card-text">{{ $concert->description ?? 'Tidak ada deskripsi.' }}</p>
                            <form action="{{ route('order.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="concert_id" value="{{ $concert->id }}">
                                <div class="mb-3">
                                    <label for="ticket_type_{{ $concert->id }}" class="form-label">Pilih Jenis Tiket:</label>
                                    <select class="form-select" id="ticket_type_{{ $concert->id }}" name="ticket_type" required>
                                        <option value="reguler">Reguler - Rp{{ number_format($concert->price, 0, ',', '.') }}</option>
                                        <option value="vip">VIP - Rp{{ number_format($concert->price * 2, 0, ',', '.') }}</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Checkout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
