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
                <a href="#" class="btn btn-primary">Lihat Detail</a>
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
@endsection
