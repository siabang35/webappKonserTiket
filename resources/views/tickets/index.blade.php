@extends('layouts.layout')

@section('title', 'Tiket Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="bi bi-ticket-perforated me-2"></i>Tiket Saya
            </h1>
            <p class="text-gray-600">Kelola semua tiket konser Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tickets as $ticket)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-semibold text-lg">
                            {{ optional($ticket->order->concert)->name ?? 'Konser Tidak Diketahui' }}
                        </h3>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            {{ $ticket->status === 'valid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ticket->status === 'valid' ? 'Valid' : 'Digunakan' }}
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-gray-600">
                            <i class="bi bi-calendar-event me-2"></i>
                            <span>{{ optional($ticket->order->concert)->date?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>{{ optional($ticket->order->concert)->venue ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="bi bi-ticket-detailed me-2"></i>
                            <span>{{ ucfirst(optional($ticket->order)->ticket_type ?? 'Tiket Tidak Diketahui') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <a href="{{ route('tickets.show', $ticket) }}"
                           class="text-primary hover:text-primary-dark">
                            <i class="bi bi-eye me-1"></i>Lihat Detail
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('tickets.download', $ticket) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-download me-1"></i>Download
                            </a>
                            @if($ticket->status === 'valid')
                            <button type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    onclick="showTransferModal('{{ $ticket->id }}')">
                                <i class="bi bi-arrow-right me-1"></i>Transfer
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                    <i class="bi bi-ticket-detailed text-4xl text-gray-400 mb-3"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Tiket</h3>
                    <p class="text-gray-600 mb-4">Anda belum memiliki tiket konser</p>
                    <a href="{{ route('concerts.index') }}" class="btn btn-primary">
                        <i class="bi bi-music-note-list me-2"></i>Lihat Konser
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transfer Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="transferForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Email Penerima</label>
                        <input type="email" name="recipient_email" class="form-control" required>
                    </div>
                    <p class="text-sm text-gray-600">
                        <i class="bi bi-info-circle me-1"></i>
                        Tiket akan ditransfer ke pengguna dengan email yang Anda masukkan
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTransferModal(ticketId) {
    const form = document.getElementById('transferForm');
    form.action = "{{ route('tickets.transfer.initiate', ':id') }}".replace(':id', ticketId);
    new bootstrap.Modal(document.getElementById('transferModal')).show();
}
</script>
@endpush
@endsection
