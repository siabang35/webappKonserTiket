@extends('layouts.layout')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="text-center mb-6">
                    <i class="bi bi-arrow-left-right text-4xl text-primary"></i>
                    <h2 class="text-2xl font-bold text-gray-800 mt-3">Transfer Tiket</h2>
                    <p class="text-gray-600">Konfirmasi transfer tiket konser</p>
                </div>

                <!-- Ticket Details -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-gray-800 mb-3">Detail Tiket</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Konser:</span>
                            <span class="font-medium">{{ $ticket->order->concert->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal:</span>
                            <span>{{ $ticket->order->concert->date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipe:</span>
                            <span>{{ ucfirst($ticket->order->ticket_type) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kode Transfer:</span>
                            <span class="font-mono font-medium">{{ $transferCode }}</span>
                        </div>
                    </div>
                </div>

                <!-- Transfer Form -->
                <form action="{{ route('tickets.transfer.complete', $transferCode) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email Penerima
                            </label>
                            <input type="email" name="recipient_email"
                                   class="form-control"
                                   value="{{ $recipientEmail }}"
                                   readonly>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Pastikan email penerima sudah benar. Transfer tiket tidak dapat dibatalkan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('tickets.show', $ticket) }}"
                               class="btn btn-secondary">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Konfirmasi Transfer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
