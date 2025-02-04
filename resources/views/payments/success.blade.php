@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-xl mx-auto px-4">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                <i class="bi bi-check-circle-fill text-3xl text-green-500"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-600">Terima kasih telah melakukan pembelian tiket konser.</p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Order ID</span>
                        <span class="font-medium">{{ $order->id }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-primary">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            Pembayaran Berhasil
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('tickets.download', $order->id) }}"
               class="block w-full bg-primary text-white text-center py-3 rounded-lg font-semibold hover:bg-primary-dark transition-colors">
                <i class="bi bi-download me-2"></i>Download Tiket
            </a>

            <a href="{{ route('dashboard') }}"
               class="block w-full bg-gray-100 text-gray-700 text-center py-3 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                <i class="bi bi-house me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
