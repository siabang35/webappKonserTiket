@extends('layouts.layout')

@section('title', 'Wishlist')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-fill text-danger me-2"></i>Wishlist Saya
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($wishlists->count() > 0)
                        <div class="row g-4">
                            @foreach($wishlists as $wishlist)
                                <div class="col-md-6 col-xl-4">
                                    <div class="concert-card card h-100 border-0 shadow-sm">
                                        <div class="concert-image-wrapper position-relative">
                                            <img src="{{ $wishlist->concert->image_url ?? asset('assets/images/placeholder.png') }}"
                                                 class="card-img-top concert-image"
                                                 alt="{{ $wishlist->concert->name }}">
                                            <div class="concert-overlay">
                                                <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $wishlist->concert->date->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-bold mb-3">{{ $wishlist->concert->name }}</h5>
                                            <div class="concert-info mb-4">
                                                <div class="d-flex align-items-center text-muted mb-2">
                                                    <i class="bi bi-geo-alt me-2"></i>
                                                    <span>{{ $wishlist->concert->location }}</span>
                                                </div>
                                                <div class="d-flex align-items-center text-muted">
                                                    <i class="bi bi-currency-dollar me-2"></i>
                                                    <span>Rp{{ number_format($wishlist->concert->price, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('concerts.show', $wishlist->concert) }}"
                                                   class="btn btn-primary">
                                                    <i class="bi bi-ticket-detailed me-2"></i>Lihat Detail
                                                </a>
                                                <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <!--<button type="submit" class="btn btn-outline-danger w-100">
                                                        <i class="bi bi-heart-fill me-2"></i>Hapus dari Wishlist
                                                    </button>!-->
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-heart display-1 text-muted mb-3"></i>
                            <h3 class="text-muted">Wishlist Anda masih kosong</h3>
                            <p class="text-muted mb-4">Tambahkan konser favorit Anda ke wishlist</p>
                            <a href="{{ route('concerts.index') }}" class="btn btn-primary">
                                <i class="bi bi-music-note-list me-2"></i>Jelajahi Konser
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
