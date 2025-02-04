@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="lead text-muted">Kelola tiket konser Anda dengan mudah dan nikmati pengalaman konser terbaik.</p>
    </div>

    <!-- Statistics -->
    <div class="row g-4 mb-5">
        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-inline-flex p-3 bg-primary bg-opacity-10 text-primary rounded-circle mb-3">
                        <i data-lucide="ticket" class="h5 mb-0"></i>
                    </div>
                    <h3 class="h5 card-title">Total Pemesanan</h3>
                    <p class="display-6 fw-bold text-primary mb-3">{{ $totalOrders ?? 0 }}</p>
                    <a href="{{ route('order.history') }}" class="text-decoration-none d-inline-flex align-items-center">
                        Lihat Riwayat
                        <i data-lucide="arrow-right" class="ms-1" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Available Concerts -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-inline-flex p-3 bg-success bg-opacity-10 text-success rounded-circle mb-3">
                        <i data-lucide="music" class="h5 mb-0"></i>
                    </div>
                    <h3 class="h5 card-title">Konser Tersedia</h3>
                    <p class="display-6 fw-bold text-success mb-3">{{ $concerts->count() }}</p>
                    <a href="#concerts" class="text-decoration-none text-success d-inline-flex align-items-center">
                        Lihat Konser
                        <i data-lucide="arrow-right" class="ms-1" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- VIP Orders -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="d-inline-flex p-3 bg-warning bg-opacity-10 text-warning rounded-circle mb-3">
                        <i data-lucide="star" class="h5 mb-0"></i>
                    </div>
                    <h3 class="h5 card-title">VIP yang Anda Pesan</h3>
                    <p class="display-6 fw-bold text-warning mb-3">{{ $vipOrders ?? 0 }}</p>
                    <a href="{{ route('order.history') }}" class="text-decoration-none text-warning d-inline-flex align-items-center">
                        Detail VIP
                        <i data-lucide="arrow-right" class="ms-1" style="width: 16px; height: 16px;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket List Section -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="h4 mb-0">Daftar Tiket Konser</h2>
                </div>
                <div class="col-auto d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i data-lucide="filter" class="me-1" style="width: 16px; height: 16px;"></i>
                            Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Semua Tiket</a></li>
                            <li><a class="dropdown-item" href="#">Tiket VIP</a></li>
                            <li><a class="dropdown-item" href="#">Tiket Regular</a></li>
                        </ul>
                    </div>
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Cari tiket...">
                        <i data-lucide="search" class="position-absolute top-50 start-0 translate-middle-y ms-3" style="width: 16px; height: 16px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Konser</th>
                        <th>Tanggal</th>
                        <th>Jenis Tiket</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets ?? [] as $ticket)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $ticket->concert->image_url ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&q=80' }}"
                                         class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $ticket->concert->name }}</div>
                                        <div class="small text-muted">{{ Str::limit($ticket->concert->venue, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $ticket->concert->date->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $ticket->concert->date->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                @if($ticket->type === 'vip')
                                    <span class="badge bg-purple bg-opacity-10 text-purple">
                                        <i data-lucide="star" class="me-1" style="width: 12px; height: 12px;"></i>
                                        VIP
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <i data-lucide="ticket" class="me-1" style="width: 12px; height: 12px;"></i>
                                        Regular
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($ticket->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i data-lucide="check-circle" class="me-1" style="width: 12px; height: 12px;"></i>
                                        Aktif
                                    </span>
                                @elseif($ticket->status === 'used')
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <i data-lucide="check" class="me-1" style="width: 12px; height: 12px;"></i>
                                        Terpakai
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i data-lucide="x-circle" class="me-1" style="width: 12px; height: 12px;"></i>
                                        Kadaluarsa
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i data-lucide="download" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-inline-flex p-4 bg-light rounded-circle mb-3">
                                    <i data-lucide="ticket-off" style="width: 32px; height: 32px;" class="text-muted"></i>
                                </div>
                                <p class="text-muted mb-3">Belum ada tiket yang dipesan</p>
                                <a href="#concerts" class="btn btn-primary">
                                    Pesan Tiket Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($tickets ?? collect())->isNotEmpty())
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        Showing <span class="fw-medium">1</span> to <span class="fw-medium">10</span> of <span class="fw-medium">20</span> results
                    </p>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <i data-lucide="chevron-left" style="width: 16px; height: 16px;"></i>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next">
                                    <i data-lucide="chevron-right" style="width: 16px; height: 16px;"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <!-- Concert List -->
    <div id="concerts" class="mb-5">
        <h2 class="text-center h3 mb-4">Daftar Konser Musik</h2>
        <div class="row g-4">
            @forelse($concerts as $concert)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $concert->image_url ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&q=80' }}"
                                 class="card-img-top"
                                 style="height: 200px; object-fit: cover;"
                                 alt="Poster {{ $concert->name }}">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary">
                                    {{ $concert->date->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="h5 card-title">{{ $concert->name }}</h3>
                            <p class="card-text text-muted small">{{ Str::limit($concert->description, 100) }}</p>

                            <form action="{{ route('order.store') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="concert_id" value="{{ $concert->id }}">

                                <div class="mb-3">
                                    <label for="ticket_type_{{ $concert->id }}" class="form-label">Jenis Tiket:</label>
                                    <select id="ticket_type_{{ $concert->id }}"
                                            name="ticket_type"
                                            class="form-select"
                                            required>
                                        <option value="reguler">Reguler - Rp{{ number_format($concert->price, 0, ',', '.') }}</option>
                                        <option value="vip">VIP - Rp{{ number_format($concert->price * 2, 0, ',', '.') }}</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="ticket_count_{{ $concert->id }}" class="form-label">Jumlah Tiket:</label>
                                    <input type="number"
                                           id="ticket_count_{{ $concert->id }}"
                                           name="ticket_count"
                                           class="form-control"
                                           value="1"
                                           min="1"
                                           max="10"
                                           required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Pesan Sekarang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="d-inline-flex p-4 bg-light rounded-circle mb-3">
                        <i data-lucide="music-off" style="width: 32px; height: 32px;" class="text-muted"></i>
                    </div>
                    <p class="text-muted">Belum ada konser tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
