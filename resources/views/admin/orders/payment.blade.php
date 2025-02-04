@extends('layouts.app')

@section('title', 'Pembayaran Tiket')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h2 class="h4 mb-1">Pembayaran Tiket Konser</h2>
                        <p class="text-muted">{{ $order->concert->name }}</p>
                    </div>

                    <div class="order-details mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Jenis Tiket</small>
                                    <strong>{{ ucfirst($order->ticket_type) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Jumlah Tiket</small>
                                    <strong>{{ $order->ticket_count }} tiket</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="detail-item">
                                    <small class="text-muted d-block">Total Pembayaran</small>
                                    <strong class="text-primary fs-4">
                                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="payment-instructions">
                        <h5 class="mb-3">Instruksi Pembayaran</h5>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Silakan selesaikan pembayaran dalam waktu 1 jam
                        </div>

                        <!-- Midtrans Payment Button -->
                        <div class="text-center">
                            <button
                                id="pay-button"
                                class="btn btn-primary btn-lg"
                                data-token="{{ $payment->payment_details['snap_token'] }}"
                            >
                                <i class="bi bi-credit-card me-2"></i>
                                Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Detail Pesanan</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <td class="text-muted">Order ID</td>
                                <td class="text-end">#{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Konser</td>
                                <td class="text-end">{{ $order->concert->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tanggal Konser</td>
                                <td class="text-end">{{ $order->concert->date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Venue</td>
                                <td class="text-end">{{ $order->concert->venue }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.midtrans.com/snap/snap.js"></script>
<script>
document.getElementById('pay-button').onclick = function() {
    snap.pay('{{ $payment->payment_details['snap_token'] }}', {
        onSuccess: function(result) {
            window.location.href = '{{ route('orders.show', $order) }}';
        },
        onPending: function(result) {
            window.location.href = '{{ route('orders.show', $order) }}';
        },
        onError: function(result) {
            alert('Pembayaran gagal, silakan coba lagi');
        },
        onClose: function() {
            alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
        }
    });
};
</script>
@endpush
@endsection
