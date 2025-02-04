@extends('layouts.layout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-4">Pembayaran Gagal</h2>
                    <p class="text-muted mb-4">
                        Maaf, pembayaran Anda tidak dapat diproses. Silakan coba lagi atau pilih metode pembayaran lain.
                    </p>

                    <!-- Order Details -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Detail Pesanan</h5>
                            <div class="row">
                                <div class="col-sm-6 text-start">
                                    <p class="mb-1"><strong>Order ID:</strong> #{{ $order->id }}</p>
                                    <p class="mb-1"><strong>Konser:</strong> {{ $order->concert->name }}</p>
                                    <p class="mb-1"><strong>Tanggal:</strong> {{ $order->concert->date->format('d M Y') }}</p>
                                </div>
                                <div class="col-sm-6 text-start">
                                    <p class="mb-1"><strong>Total Pembayaran:</strong> Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-danger">Gagal</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                        <a href="{{ route('payment.show', $order->id) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-repeat me-2"></i>Coba Lagi
                        </a>
                        <a href="{{ route('order.history') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@end
