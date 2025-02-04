@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Detail Pesanan #{{ $order->id }}</h4>
                        <span class="badge bg-{{ $order->status_color }} px-3 py-2">
                            {{ $order->status_text }}
                        </span>
                    </div>

                    <div class="order-timeline mb-4">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ match($order->status) {
                                'pending' => '25%',
                                'processing' => '50%',
                                'completed' => '100%',
                                default => '0%'
                            } }}"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">Pesanan Dibuat</small>
                            <small class="text-muted">Menunggu Pembayaran</small>
                            <small class="text-muted">Diproses</small>
                            <small class="text-muted">Selesai</small>
                        </div>
                    </div>

                    <!-- Concert Details -->
                    <div class="concert-details mb-4">
                        <h5 class="mb-3">Informasi Konser</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Nama Konser</small>
                                    <strong>{{ $order->concert->name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Tanggal</small>
                                    <strong>{{ $order->concert->date->format('d M Y') }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Waktu</small>
                                    <strong>{{ $order->concert->time }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Venue</small>
                                    <strong>{{ $order->concert->venue }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="ticket-details mb-4">
                        <h5 class="mb-3">Detail Tiket</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Jenis Tiket</td>
                                    <td class="text-end">
                                        <span class="badge bg-{{ $order->ticket_type === 'vip' ? 'warning' : 'secondary' }}">
                                            {{ ucfirst($order->ticket_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jumlah Tiket</td>
                                    <td class="text-end">{{ $order->ticket_count }} tiket</td>
                                </tr>
                                <tr>
                                    <td>Harga per Tiket</td>
                                    <td class="text-end">
                                        Rp{{ number_format($order->total_amount / $order->ticket_count, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($order->status === 'completed')
                    <!-- E-Ticket Download -->
                    <div class="text-center">
                        <a href="{{ route('tickets.download', $order) }}" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i>
                            Download E-Ticket
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->payment)
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Informasi Pembayaran</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td>Metode Pembayaran</td>
                                <td class="text-end">{{ ucfirst($order->payment->payment_method) }}</td>
                            </tr>
                            <tr>
                                <td>Status Pembayaran</td>
                                <td class="text-end">
                                    <span class="badge bg-{{ match($order->payment->status) {
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'secondary'
                                    } }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @if($order->payment->transaction_id)
                            <tr>
                                <td>ID Transaksi</td>
                                <td class="text-end">{{ $order->payment->transaction_id }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
