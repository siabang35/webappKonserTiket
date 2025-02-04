@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pembayaran Tiket</h4>
                </div>
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="order-summary mb-4">
                        <h5 class="border-bottom pb-2">Detail Pesanan</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Konser:</strong> {{ $order->concert->name }}</p>
                                <p><strong>Tanggal:</strong> {{ $order->concert->date->format('d M Y') }}</p>
                                <p><strong>Lokasi:</strong> {{ $order->concert->venue }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Tipe Tiket:</strong> {{ ucfirst($order->ticket_type) }}</p>
                                <p><strong>Jumlah Tiket:</strong> {{ $order->ticket_count }}</p>
                                <p><strong>Total:</strong> Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods mb-4">
                        <h5 class="border-bottom pb-2">Pilih Metode Pembayaran</h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="payment-method-card">
                                    <input type="radio" name="payment_method" id="credit_card"
                                           value="credit_card" class="btn-check">
                                    <label class="btn btn-outline-primary w-100" for="credit_card">
                                        <i class="bi bi-credit-card-2-front me-2"></i>Kartu Kredit
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-method-card">
                                    <input type="radio" name="payment_method" id="bank_transfer"
                                           value="bank_transfer" class="btn-check">
                                    <label class="btn btn-outline-primary w-100" for="bank_transfer">
                                        <i class="bi bi-bank me-2"></i>Transfer Bank
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="payment-method-card">
                                    <input type="radio" name="payment_method" id="e_wallet"
                                           value="e_wallet" class="btn-check">
                                    <label class="btn btn-outline-primary w-100" for="e_wallet">
                                        <i class="bi bi-wallet2 me-2"></i>E-Wallet
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Timer -->
                    <div class="alert alert-warning">
                        <i class="bi bi-clock-history me-2"></i>
                        Selesaikan pembayaran dalam <span id="payment-countdown">24:00:00</span>
                    </div>

                    <!-- Payment Button -->
                    <div class="d-grid gap-2">
                        <button type="button" id="pay-button" class="btn btn-primary btn-lg" disabled>
                            <i class="bi bi-lock-fill me-2"></i>Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const payButton = document.getElementById('pay-button');

    // Enable payment button when payment method is selected
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            payButton.disabled = false;
        });
    });

    // Payment countdown timer
    function startCountdown(duration) {
        let timer = duration;
        const countdownElement = document.getElementById('payment-countdown');

        const countdown = setInterval(function() {
            const hours = Math.floor(timer / 3600);
            const minutes = Math.floor((timer % 3600) / 60);
            const seconds = timer % 60;

            countdownElement.textContent =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');

            if (--timer < 0) {
                clearInterval(countdown);
                countdownElement.textContent = 'Waktu habis';
                payButton.disabled = true;
                window.location.href = '{{ route("order.history") }}';
            }
        }, 1000);
    }

    // Start 24-hour countdown
    startCountdown(24 * 60 * 60);

    // Handle payment button click
    payButton.addEventListener('click', async function() {
        const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

        try {
            const response = await fetch('{{ route("payment.process", $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ payment_method: selectedMethod })
            });

            const data = await response.json();

            if (data.success && data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("payment.success", $order->id) }}';
                    },
                    onPending: function(result) {
                        alert('Pembayaran pending, silakan selesaikan pembayaran Anda');
                    },
                    onError: function(result) {
                        window.location.href = '{{ route("payment.failed", $order->id) }}';
                    },
                    onClose: function() {
                        alert('Anda menutup popup pembayaran sebelum menyelesaikan pembayaran');
                    }
                });
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pembayaran');
            }
        } catch (error) {
            console.error('Payment error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran');
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.payment-method-card {
    height: 100%;
}
.payment-method-card label {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 1rem;
}
.payment-method-card input:checked + label {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}
</style>
@endpush
@endsection
