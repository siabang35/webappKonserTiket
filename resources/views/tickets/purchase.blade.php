@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="bi bi-ticket-detailed me-2"></i>Pembelian Tiket
                </h2>

                <div class="space-y-6">
                    <!-- Concert Details -->
                    <div class="flex items-start justify-between pb-4 border-b border-gray-200">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $concert->name }}</h3>
                            <div class="text-sm text-gray-600 mt-1">
                                <p><i class="bi bi-calendar-event me-2"></i>{{ $concert->date->format('d M Y') }}</p>
                                <p><i class="bi bi-geo-alt me-2"></i>{{ $concert->venue }}</p>
                                <p><i class="bi bi-clock me-2"></i>{{ $concert->time }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            {{ $concert->status === 'upcoming' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $concert->status === 'upcoming' ? 'Akan Datang' : 'Terbatas' }}
                        </span>
                    </div>

                    <!-- Ticket Type Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Detail Tiket {{ ucfirst($type) }}</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Harga Tiket:</span>
                                <span class="font-medium">
                                    Rp{{ number_format($type === 'vip' ? $concert->price * 2 : $concert->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fasilitas:</span>
                                <span class="font-medium">
                                    {{ $type === 'vip' ? 'Akses VIP + Merchandise' : 'Akses Reguler' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Form -->
                    <form action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="concert_id" value="{{ $concert->id }}">
                        <input type="hidden" name="ticket_type" value="{{ $type }}">

                        <div class="space-y-4">
                            <!-- Quantity Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Tiket (Maksimal 10)
                                </label>
                                <div class="flex items-center space-x-3">
                                    <button type="button" class="decrease-quantity p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" name="ticket_count" value="1" min="1" max="10"
                                           class="form-control w-20 text-center border-gray-300 rounded-md shadow-sm">
                                    <button type="button" class="increase-quantity p-2 rounded-full bg-gray-100 hover:bg-gray-200">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                @error('ticket_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Price -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">Total Pembayaran:</span>
                                    <span class="text-xl font-bold text-primary" id="total-price">
                                        Rp{{ number_format($type === 'vip' ? $concert->price * 2 : $concert->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('concerts.show', $concert) }}"
                                   class="btn btn-secondary">
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-cart-check me-2"></i>Beli Tiket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('input[name="ticket_count"]');
    const decreaseBtn = document.querySelector('.decrease-quantity');
    const increaseBtn = document.querySelector('.increase-quantity');
    const totalPriceElement = document.getElementById('total-price');
    const basePrice = {{ $type === 'vip' ? $concert->price * 2 : $concert->price }};

    function updateTotalPrice() {
        const quantity = parseInt(quantityInput.value);
        const total = basePrice * quantity;
        totalPriceElement.textContent = `Rp${total.toLocaleString('id-ID')}`;
    }

    decreaseBtn.addEventListener('click', () => {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            updateTotalPrice();
        }
    });

    increaseBtn.addEventListener('click', () => {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < 10) {
            quantityInput.value = currentValue + 1;
            updateTotalPrice();
        }
    });

    quantityInput.addEventListener('change', updateTotalPrice);
});
</script>
@endpush
