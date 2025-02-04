@extends('layouts.layout')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-bell me-2"></i>Notifikasi
                        </h5>
                        @if($notifications->count() > 0)
                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check-all me-2"></i>Tandai Semua Dibaca
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    @forelse($notifications as $notification)
                    <div class="notification-item p-3 mb-3 rounded-3 {{ $notification->read_at ? 'bg-light' : 'bg-light border-start border-4 border-primary' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0 {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                {{ $notification->data['title'] ?? 'Notifikasi' }}
                            </h6>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-2 {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        @if(!$notification->read_at)
                        <div class="d-flex justify-content-end">
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-primary p-0">
                                    <i class="bi bi-check me-1"></i>Tandai Dibaca
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Notifikasi</h5>
                        <p class="text-muted mb-0">Anda akan menerima notifikasi untuk aktivitas penting</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
