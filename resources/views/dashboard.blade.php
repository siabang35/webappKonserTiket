@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Banner Slider -->
    <div class="welcome-banner-slider mb-5 rounded-4 position-relative overflow-hidden">
        <div class="banner-slides">
            <!-- Welcome Slide -->
            <div class="banner-slide active">
                <div class="banner-content p-5 text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="display-4 fw-bold mb-3">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                            <p class="lead mb-4">Temukan konser favorit Anda dan nikmati pengalaman musik terbaik bersama kami.</p>
                            <div class="d-flex gap-3">
                                <a href="#upcoming" class="btn btn-light btn-lg">
                                    <i class="bi bi-calendar-event me-2"></i>Konser Mendatang
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block text-center">
                            <i class="bi bi-music-note-beamed display-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promosi Banner -->
            <div class="banner-slide">
                <div class="banner-content p-5 bg-danger text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2 class="display-4 fw-bold mb-3">Diskon Spesial 50% untuk Tiket VIP! 🎉</h2>
                            <p class="lead mb-4">Dapatkan diskon 50% untuk tiket VIP pada konser-konser mendatang. Hanya untuk pengguna terdaftar!</p>
                            <div class="d-flex gap-3">
                                <a href="#upcoming" class="btn btn-light btn-lg">
                                    <i class="bi bi-calendar-event me-2"></i>Konser Mendatang
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block text-center">
                            <i class="bi bi-gift display-1"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured Concerts Loop -->
            @foreach($featuredConcerts as $concert)
            <div class="banner-slide">
                <div class="banner-content p-5 text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <span class="badge bg-warning mb-3">Konser Unggulan</span>
                            <h2 class="display-4 fw-bold mb-3">{{ $concert->name }}</h2>
                            <p class="lead mb-4">{{ Str::limit($concert->description, 120) }}</p>
                            <div class="d-flex gap-3 align-items-center mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    <span>{{ $concert->date->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <span>{{ $concert->venue }}</span>
                                </div>
                            </div>
                            <a href="{{ route('concerts.show', $concert) }}" class="btn btn-light btn-lg">
                                <i class="bi bi-ticket-detailed me-2"></i>Lihat Detail
                            </a>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <img src="{{ $concert->image_url ?? asset('assets/images/placeholder.png') }}"
                                 class="img-fluid rounded-3 banner-image"
                                 alt="{{ $concert->name }}">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Banner Navigation -->
        <button class="banner-nav prev" aria-label="Previous slide">
            <i class="bi bi-chevron-left"></i>
        </button>
        <button class="banner-nav next" aria-label="Next slide">
            <i class="bi bi-chevron-right"></i>
        </button>

        <!-- Banner Indicators -->
        <div class="banner-indicators">
            <button class="indicator active" data-slide="0"></button>
            <button class="indicator" data-slide="1"></button>
            @foreach($featuredConcerts as $index => $concert)
            <button class="indicator" data-slide="{{ $index + 2 }}"></button>
            @endforeach
        </div>
    </div>
</div>

<!-- Additional Styling for Better Banner Layout -->
<style>
    .welcome-banner-slider {
        max-height: 600px;
        position: relative;
    }

    .banner-slide {
        min-height: 100vh;
        background-size: cover;
        background-position: center;
        position: relative;
        color: white;
        display: flex;
        justify-content: center;
    }

    .banner-content {
        z-index: 2;
    }

    .banner-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.4);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
        cursor: pointer;
    }

    .banner-nav.prev {
        left: 10px;
    }

    .banner-nav.next {
        right: 10px;
    }

    .banner-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        display: flex;
        gap: 8px;
    }

    .indicator {
        background-color: rgba(255, 255, 255, 0.7);
        border: none;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        transition: background-color 0.3s;
        cursor: pointer;
    }

    .indicator.active {
        background-color: #fff;
    }

    .indicator:hover {
        background-color: #ccc;
    }

    .banner-image {
        max-height: 350px;
        object-fit: cover;
    }

    .btn-lg {
        font-size: 1.2rem;
        padding: 12px 24px;
    }

    @media (max-width: 991px) {
        .banner-nav {
            display: none;
        }
    }
</style>



    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Quick Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-ticket-perforated-fill text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle text-muted mb-1">Total Pemesanan</h6>
                                    <h2 class="card-title mb-0">{{ $totalOrders ?? 0 }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: {{ min(($totalOrders ?? 0) * 10, 100) }}%"></div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-arrow-up-circle-fill text-success me-1"></i>
                                {{ $totalOrders ?? 0 }} tiket bulan ini
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-calendar-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle text-muted mb-1">Konser Tersedia</h6>
                                    <h2 class="card-title mb-0">{{ $concerts->count() }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: {{ min($concerts->count() * 10, 100) }}%"></div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-calendar-plus text-success me-1"></i>
                                {{ $concerts->count() }} konser baru bulan ini
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-star-fill text-warning fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle text-muted mb-1">Tiket VIP</h6>
                                    <h2 class="card-title mb-0">{{ $vipOrders ?? 0 }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 4px;">
                                <div class="progress-bar bg-warning" style="width: {{ min(($vipOrders ?? 0) * 10, 100) }}%"></div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-crown text-warning me-1"></i>
                                {{ $vipOrders ?? 0 }} VIP aktif
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="stat-card card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="stat-icon bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-wallet2 text-info fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle text-muted mb-1">Total Pengeluaran</h6>
                                    <h2 class="card-title mb-0">Rp{{ number_format($totalSpent ?? 0, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                            <div class="progress mb-3" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: {{ min(($totalSpent ?? 0) / 1000000 * 100, 100) }}%"></div>
                            </div>
                            <small class="text-muted">
                                <i class="bi bi-graph-up-arrow text-info me-1"></i>
                                Pengeluaran bulan ini
                            </small>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Concert Filter & Search -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="search-box">
                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                                <input type="text" class="form-control ps-5" id="searchConcert" placeholder="Cari konser...">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="d-flex gap-3 flex-wrap">
                                <select class="form-select w-auto" id="filterMonth">
                                    <option value="">Pilih Bulan</option>
                                    @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select w-auto" id="filterGenre">
                                    <option value="">Semua Genre</option>
                                    @foreach($genres ?? ['pop', 'rock', 'jazz', 'classical'] as $genre)
                                    <option value="{{ $genre }}">{{ ucfirst($genre) }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select w-auto" id="sortConcerts">
                                    <option value="date">Urutkan: Tanggal</option>
                                    <option value="price-asc">Harga: Rendah ke Tinggi</option>
                                    <option value="price-desc">Harga: Tinggi ke Rendah</option>
                                    <option value="name">Nama: A-Z</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



           <!-- Concert Grid -->
<div id="concerts">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            <i class="bi bi-music-note-list me-2"></i>Daftar Konser
        </h2>
        <div class="view-options">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" data-view="grid">
                    <i class="bi bi-grid"></i>
                </button>
                <button type="button" class="btn btn-outline-primary" data-view="list">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($concerts as $concert)
        <div class="col-md-6 col-xl-4">
            <div class="concert-card card h-100 border-0 shadow-sm">
                <!-- Concert Image -->
                <div class="concert-image-wrapper position-relative">
                    <img src="{{ $concert->image_url ?? asset('assets/images/placeholder.png') }}"
                         class="card-img-top concert-image"
                         alt="{{ $concert->name }}">
                    <div class="concert-overlay">
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3">
                            <i class="bi bi-calendar-event me-1"></i>
                            {{ $concert->date->format('d M Y') }}
                        </span>
                        @if($concert->tickets_left < 50)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-3">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Sisa {{ $concert->tickets_left }} tiket
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Concert Details -->
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title fw-bold mb-0">{{ $concert->name }}</h5>
                        <span class="badge bg-{{ $concert->status === 'upcoming' ? 'success' : 'warning' }}">
                            {{ $concert->status === 'upcoming' ? 'Akan Datang' : 'Terbatas' }}
                        </span>
                    </div>

                    <!-- Concert Info -->
                    <div class="concert-info mb-4">
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>{{ $concert->venue }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted mb-2">
                            <i class="bi bi-clock me-2"></i>
                            <span>{{ $concert->time }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-music-note me-2"></i>
                            <span>{{ $concert->genre }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="concert-description mb-4">{{ Str::limit($concert->description, 100) }}</p>

                    <!-- Ticket Booking Form -->

<form action="{{ route('payment.process', ['order' => $order->id]) }}" method="POST" class="ticket-form">
    @csrf
    <!-- Ticket Type Selection -->
    <div class="mb-4">
        <label class="form-label d-flex justify-content-between">
            <span><i class="bi bi-ticket-detailed me-2"></i>Jenis Tiket</span>
            <a href="#" class="text-decoration-none" data-bs-toggle="tooltip"
               title="Klik untuk informasi detail tiket">
                <i class="bi bi-info-circle"></i>
            </a>
        </label>
        <div class="ticket-options">
            <div class="ticket-type-card mb-3">
                <input class="btn-check ticket-type" type="radio" name="ticket_type"
                       id="regular_{{ $concert->id }}" value="regular"
                       data-price="{{ $concert->price }}" checked>
                <label class="btn btn-outline-primary w-100 p-3 rounded-3"
                       for="regular_{{ $concert->id }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Regular</h6>
                            <small class="text-muted">Akses standar ke konser</small>
                        </div>
                        <div class="text-end">
                            <div class="fs-5 fw-bold text-primary">
                                Rp{{ number_format($concert->price, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">per tiket</small>
                        </div>
                    </div>
                </label>
            </div>

            <div class="ticket-type-card">
                <input class="btn-check ticket-type" type="radio" name="ticket_type"
                       id="vip_{{ $concert->id }}" value="vip"
                       data-price="{{ $concert->price * 2 }}">
                <label class="btn btn-outline-primary w-100 p-3 rounded-3"
                       for="vip_{{ $concert->id }}">
                    <span class="position-absolute top-0 end-0 mt-2 me-2">
                        <span class="badge bg-warning text-dark">Premium</span>
                    </span>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">VIP</h6>
                            <small class="text-muted">Akses premium & merchandise eksklusif</small>
                        </div>
                        <div class="text-end">
                            <div class="fs-5 fw-bold text-primary">
                                Rp{{ number_format($concert->price * 2, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">per tiket</small>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <!-- Quantity Selection -->
    <div class="mb-4">
        <label class="form-label d-flex justify-content-between">
            <span><i class="bi bi-123 me-2"></i>Jumlah Tiket</span>
            <span class="text-muted">Maksimal 10 tiket</span>
        </label>
        <div class="quantity-selector">
            <div class="input-group">
                <button type="button" class="btn btn-primary decrease-quantity">
                    <i class="bi bi-dash-lg"></i>
                </button>
                <input type="number"
                       class="form-control text-center ticket-quantity"
                       name="ticket_count"
                       value="1"
                       min="1"
                       max="10"
                       required>
                <button type="button" class="btn btn-primary increase-quantity">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>
            <div class="progress mt-2" style="height: 6px;">
                <div class="progress-bar bg-primary quantity-progress" role="progressbar"
                     style="width: 10%;" aria-valuenow="1" aria-valuemin="1" aria-valuemax="10"></div>
            </div>
            <small class="text-muted tickets-left-info">
                Tersisa <span class="fw-bold">{{ $concert->tickets_left }}</span> tiket
            </small>
        </div>
    </div>

    <!-- Price Breakdown -->
    <div class="price-breakdown mb-4 p-3 bg-light rounded-3">
        <h6 class="mb-3">Rincian Harga</h6>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Harga per Tiket:</span>
            <span class="ticket-price">Rp{{ number_format($concert->price, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Jumlah Tiket:</span>
            <span class="ticket-quantity-display">1</span>
        </div>
        <hr class="my-2">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold">Total Pembayaran:</span>
            <span class="total-amount fs-5 fw-bold text-primary">
                Rp{{ number_format($concert->price, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Payment Method Selection (Midtrans Integration) -->
<div class="mb-4">
    <label class="form-label" for="payment_method">
        <i class="bi bi-credit-card me-2"></i>Metode Pembayaran
    </label>
    <select name="payment_method" id="payment_method" class="form-select" required aria-describedby="paymentMethodHelp">
        <option value="" disabled selected>Pilih metode pembayaran</option>
        <option value="credit_card">Kartu Kredit</option>
        <option value="bank_transfer">Transfer Bank</option>
        <option value="gopay">GoPay</option>
        <!-- Opsi lain yang bisa ditambahkan sesuai dengan metode yang didukung Midtrans -->
    </select>
    <small id="paymentMethodHelp" class="form-text text-muted">Pilih metode pembayaran yang Anda inginkan.</small>
</div>


    <!-- Submit Button -->
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-cart-plus me-2"></i>Pesan Sekarang
        </button>
        <button type="button" class="btn btn-outline-secondary"
                data-bs-toggle="modal" data-bs-target="#concertDetail_{{ $concert->id }}">
            <i class="bi bi-info-circle me-2"></i>Lihat Detail Konser
        </button>
    </div>
</form>


                </div>
            </div>
        </div>
        <script>
    // Ticket Quantity Controls
    document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.ticket-form');

    forms.forEach(form => {
        const quantityInput = form.querySelector('.ticket-quantity');
        const decreaseBtn = form.querySelector('.decrease-quantity');
        const increaseBtn = form.querySelector('.increase-quantity');
        const ticketTypeInputs = form.querySelectorAll('.ticket-type');
        const totalAmountSpan = form.querySelector('.total-amount');
        const ticketPriceSpan = form.querySelector('.ticket-price');
        const ticketQuantityDisplay = form.querySelector('.ticket-quantity-display');
        const quantityProgress = form.querySelector('.quantity-progress');

        // Format price dengan pemisah ribuan
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price).replace('IDR', 'Rp');
        }

        // Fungsi untuk memperbarui total dan tampilan
        function updateTotal() {
            // Ambil nilai quantity dan pastikan valid
            const quantity = Math.max(1, Math.min(10, parseInt(quantityInput.value) || 1));
            quantityInput.value = quantity; // Update input value

            // Ambil harga dasar dari tiket yang dipilih
            const selectedTicketType = form.querySelector('.ticket-type:checked');
            const basePrice = parseInt(selectedTicketType.dataset.price);

            // Hitung total
            const total = quantity * basePrice;

            // Update tampilan harga per tiket
            ticketPriceSpan.textContent = formatPrice(basePrice);

            // Update tampilan jumlah tiket
            ticketQuantityDisplay.textContent = quantity;

            // Update tampilan total pembayaran
            totalAmountSpan.textContent = formatPrice(total);

            // Update progress bar
            const progressPercentage = (quantity / 10) * 100;
            quantityProgress.style.width = `${progressPercentage}%`;
            quantityProgress.setAttribute('aria-valuenow', quantity);

            // Update status tombol
            decreaseBtn.disabled = quantity <= 1;
            increaseBtn.disabled = quantity >= 10;
        }

        // Event listener untuk tombol kurang
        decreaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotal();
            }
        });

        // Event listener untuk tombol tambah
        increaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue < 10) {
                quantityInput.value = currentValue + 1;
                updateTotal();
            }
        });

        // Event listener untuk input manual
        quantityInput.addEventListener('input', () => {
            // Hapus karakter non-numerik
            quantityInput.value = quantityInput.value.replace(/[^0-9]/g, '');

            // Konversi ke number dan batasi range
            let value = parseInt(quantityInput.value) || 1;
            value = Math.max(1, Math.min(10, value));
            quantityInput.value = value;

            // Update total dan tampilan
            updateTotal();
        });

        // Event listener untuk perubahan jenis tiket
        ticketTypeInputs.forEach(input => {
            input.addEventListener('change', () => {
                updateTotal();
            });
        });

        // Validasi form sebelum submit
        form.addEventListener('submit', function(e) {
            const quantity = parseInt(quantityInput.value) || 0;
            if (quantity < 1 || quantity > 10) {
                e.preventDefault();
                alert('Jumlah tiket harus antara 1 dan 10');
            }
        });

        // Inisialisasi pertama kali
        updateTotal();
    });

    // Inisialisasi tooltips jika Bootstrap tersedia
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    }
});

// Mengambil tombol pembayaran
const payButton = document.querySelector('.btn-primary'); // pastikan ini adalah tombol yang mengirimkan form

payButton.addEventListener('click', async function(event) {
    event.preventDefault(); // Mencegah pengiriman form otomatis

    // Ambil metode pembayaran yang dipilih
    const selectedMethod = document.querySelector('select[name="payment_method"]').value;

    // Ambil data form yang diperlukan (misalnya: harga total, jumlah tiket, jenis tiket)
    const totalAmount = document.querySelector('.total-amount').textContent.replace('Rp', '').replace(',', '');
    const ticketType = document.querySelector('input[name="ticket_type"]:checked').value;
    const ticketQuantity = document.querySelector('input[name="ticket_count"]').value;

    // Data untuk permintaan API ke server
    const paymentData = {
        total_amount: totalAmount,
        ticket_type: ticketType,
        ticket_quantity: ticketQuantity,
        payment_method: selectedMethod
    };

    try {
        // Kirimkan permintaan ke server untuk memulai transaksi Midtrans
        const response = await fetch('/payment/initiate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(paymentData)
        });

        const data = await response.json();

        // Pastikan response sukses
        if (data.status_code === '200') {
            // Arahkan pengguna ke halaman pembayaran Midtrans
            window.location.href = data.redirect_url; // URL yang diberikan oleh Midtrans
        } else {
            // Menampilkan pesan error jika transaksi gagal
            alert('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
        }
    } catch (error) {
        // Tangani jika terjadi error pada permintaan
        console.error('Terjadi kesalahan dalam menghubungi server:', error);
        alert('Terjadi kesalahan dalam menghubungi server.');
    }
});


</script>
                    <!-- Concert Detail Modal -->
                    <div class="modal fade" id="concertDetail_{{ $concert->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header border-0 bg-primary text-white">
                                    <h5 class="modal-title">Detail Konser</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ $concert->image_url ?? asset('assets/images/placeholder.png') }}"
                                                 class="img-fluid rounded-3 mb-3"
                                                 alt="{{ $concert->name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="fw-bold mb-3">{{ $concert->name }}</h4>
                                            <div class="concert-details">
                                                <div class="mb-3">
                                                    <h6 class="text-muted mb-2">Deskripsi</h6>
                                                    <p>{{ $concert->description }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="text-muted mb-2">Informasi Venue</h6>
                                                    <p class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $concert->venue }}</p>
                                                    <p class="mb-1"><i class="bi bi-calendar-event me-2"></i>{{ $concert->date->format('d M Y') }}</p>
                                                    <p><i class="bi bi-clock me-2"></i>{{ $concert->time }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <h6 class="text-muted mb-2">Genre</h6>
                                                    <p><i class="bi bi-music-note me-2"></i>{{ $concert->genre }}</p>
                                                </div>
                                                <div>
                                                    <h6 class="text-muted mb-2">Fasilitas</h6>
                                                    <ul class="list-unstyled">
                                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Parkir Gratis</li>
                                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Merchandise Eksklusif</li>
                                                        <li><i class="bi bi-check-circle-fill text-success me-2"></i>Food & Beverage</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center p-5 bg-light rounded-4">
                            <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
                            <h3 class="text-muted">Belum ada konser tersedia saat ini.</h3>
                            <p class="text-muted">Silakan cek kembali nanti untuk update terbaru.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar mb-3">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                 class="rounded-circle"
                                 width="80"
                                 height="80"
                                 alt="{{ auth()->user()->name }}">
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-person-gear me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
                        <!-- Recent Activity Timeline -->
                        <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-activity me-2"></i>Aktivitas Terbaru
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @forelse($recentActivities ?? [] as $activity)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-{{ $activity->type_color }} text-white">
                                <i class="bi bi-{{ $activity->icon }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                <p class="text-muted mb-0">{{ $activity->description }}</p>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-clock-history display-4 mb-3"></i>
                            <p>Belum ada aktivitas terbaru</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <<!-- Upcoming Concerts -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0">
            <i class="bi bi-calendar-event me-2"></i>Konser Mendatang
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="upcoming-concerts">
            @forelse($upcomingConcerts ?? [] as $concert)
            <div class="upcoming-concert-item mb-3">
                <a href="{{ route('concerts.show', $concert) }}" class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ $concert->image_url ?? asset('assets/images/placeholder.png') }}"
                                 class="rounded"
                                 width="60"
                                 height="60"
                                 alt="{{ $concert->name }}">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ $concert->name }}</h6>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $concert->date->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x display-4 mb-3"></i>
                <p>Tidak ada konser mendatang</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
            <!-- Recent Orders -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt me-2"></i>Pemesanan Terakhir
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="recent-orders">
                        @forelse($recentOrders ?? [] as $order)
                        <div class="recent-order-item mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $order->concert->name }}</h6>
                                    <p class="text-muted small mb-0">
                                        {{ $order->ticket_type }} - {{ $order->ticket_count }} tiket
                                    </p>
                                </div>
                                <span class="badge bg-{{ $order->status_color }}">
                                    {{ $order->status_text }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-receipt-cutoff display-4 mb-3"></i>
                            <p>Belum ada pemesanan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-link-45deg me-2"></i>Akses Cepat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('order.history') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Pemesanan
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-heart me-2"></i>Wishlist
                        </a>
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-bell me-2"></i>Notifikasi
                        </a>
                         <a href="{{ route('help.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-question-circle me-2"></i>Bantuan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Banner Slider */
.welcome-banner-slider {
    position: relative;
    height: 400px;
    background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
}

.banner-slides {
    height: 100%;
    position: relative;
}

.banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    display: none;
}

.banner-slide.active {
    opacity: 1;
    display: block;
}

.banner-image {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    transform: perspective(1000px) rotateY(-15deg);
    transition: transform 0.3s ease;
}

.banner-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    color: white;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
}

.banner-nav:hover {
    background: rgba(255, 255, 255, 0.3);
}

.banner-nav.prev {
    left: 20px;
}

.banner-nav.next {
    right: 20px;
}

.banner-indicators {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 10;
}

.indicator {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.3s ease;
}

.indicator.active {
    background: white;
    transform: scale(1.2);
}

/* Stats Cards */
.stat-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
    border-left: 2px solid #e9ecef;
    padding-left: 1.5rem;
}

.timeline-item:last-child {
    border-left-color: transparent;
}

.timeline-icon {
    position: absolute;
    left: -1rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

/* Concert Cards */
.concert-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.concert-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.concert-image-wrapper {
    height: 200px;
    overflow: hidden;
}

.concert-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.concert-card:hover .concert-image {
    transform: scale(1.05);
}

.concert-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0));
}

/* Ticket Form */
.ticket-type-card label {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
}

.ticket-type-card .btn-check:checked + label {
    border-color: #0d6efd;
    background-color: #f8f9ff;
    box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 25%);
}

.quantity-selector .input-group {
    width: 200px;
    margin: 0 auto;
}

.quantity-selector .form-control {
    font-size: 1.25rem;
    font-weight: bold;
}

.quantity-selector .btn {
    width: 48px;
}

.price-breakdown {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.tickets-left-info {
    display: block;
    text-align: center;
    margin-top: 0.5rem;
}

/* Animations */
.total-amount {
    transition: all 0.3s ease;
}

@keyframes highlight {
    0% { background-color: #fff3cd; }
    100% { background-color: transparent; }
}

.price-changed {
    animation: highlight 1s ease;
}
.form-check-label {
    width: 100%;
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.2s ease;
}

.form-check-input:checked + .form-check-label {
    background-color: #f8f9fa;
}

/* Sidebar */
.avatar img {
    border: 3px solid #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.upcoming-concert-item,
.recent-order-item {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.upcoming-concert-item:last-child,
.recent-order-item:last-child {
    padding-bottom: 0;
    border-bottom: none;
}

/* Animations */
@keyframes slideIn {
    from {
        transform: translateY(1rem);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate {
    animation: slideIn 0.3s ease;
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .welcome-banner-slider {
        height: 500px;
    }

    .banner-content {
        text-align: center;
    }

    .banner-image {
        margin-top: 2rem;
    }
}

@media (max-width: 767.98px) {
    .welcome-banner-slider {
        height: 600px;
    }

    .stat-card {
        margin-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Banner Slider
    const bannerSlider = {
        currentSlide: 0,
        slides: document.querySelectorAll('.banner-slide'),
        indicators: document.querySelectorAll('.indicator'),
        autoPlayInterval: null,

        init() {
            this.addEventListeners();
            this.startAutoPlay();
        },

        addEventListeners() {
            document.querySelector('.banner-nav.prev').addEventListener('click', () => this.prevSlide());
            document.querySelector('.banner-nav.next').addEventListener('click', () => this.nextSlide());

            this.indicators.forEach((indicator, index) => {
                indicator.addEventListener('click', () => this.goToSlide(index));
            });

            // Pause autoplay on hover
            document.querySelector('.welcome-banner-slider').addEventListener('mouseenter', () => this.pauseAutoPlay());
            document.querySelector('.welcome-banner-slider').addEventListener('mouseleave', () => this.startAutoPlay());
        },

        goToSlide(index) {
            this.slides[this.currentSlide].classList.remove('active');
            this.indicators[this.currentSlide].classList.remove('active');

            this.currentSlide = index;

            this.slides[this.currentSlide].classList.add('active');
            this.indicators[this.currentSlide].classList.add('active');
        },

        nextSlide() {
            const next = (this.currentSlide + 1) % this.slides.length;
            this.goToSlide(next);
        },

        prevSlide() {
            const prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
            this.goToSlide(prev);
        },

        startAutoPlay() {
            this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
        },

        pauseAutoPlay() {
            clearInterval(this.autoPlayInterval);
        }
    };

    bannerSlider.init();



    // View Toggle
    const viewButtons = document.querySelectorAll('[data-view]');
    const concertsContainer = document.querySelector('#concerts .row');

    viewButtons.forEach(button => {
        button.addEventListener('click', () => {
            viewButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const view = button.dataset.view;
            concertsContainer.classList.remove('list-view', 'grid-view');
            concertsContainer.classList.add(`${view}-view`);

            if (view === 'list') {
                document.querySelectorAll('.col-md-6').forEach(col => {
                    col.classList.remove('col-md-6', 'col-xl-4');
                    col.classList.add('col-12');
                });
            } else {
                document.querySelectorAll('.col-12').forEach(col => {
                    col.classList.remove('col-12');
                    col.classList.add('col-md-6', 'col-xl-4');
                });
            }
        });
    });

    // Search and Filter Functionality
    const searchInput = document.getElementById('searchConcert');
    const filterMonth = document.getElementById('filterMonth');
    const filterGenre = document.getElementById('filterGenre');
    const sortSelect = document.getElementById('sortConcerts');
    const concertCards = document.querySelectorAll('.concert-card');

    function filterConcerts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedMonth = filterMonth.value;
        const selectedGenre = filterGenre.value.toLowerCase();

        concertCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const description = card.querySelector('.concert-description').textContent.toLowerCase();
            const genre = card.querySelector('.concert-info .bi-music-note').nextElementSibling.textContent.toLowerCase();
            const date = new Date(card.querySelector('.badge .bi-calendar-event').parentElement.textContent.trim());

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesMonth = !selectedMonth || (date.getMonth() + 1) === parseInt(selectedMonth);
            const matchesGenre = !selectedGenre || genre.includes(selectedGenre);

            if (matchesSearch && matchesMonth && matchesGenre) {
                card.closest('.col-md-6, .col-12').style.display = '';
            } else {
                card.closest('.col-md-6, .col-12').style.display = 'none';
            }
        });
    }

    function sortConcerts() {
        const sortBy = sortSelect.value;
        const concerts = Array.from(concertCards);
        const container = document.querySelector('#concerts .row');

        concerts.sort((a, b) => {
            if (sortBy === 'date') {
                const dateA = new Date(a.querySelector('.badge').textContent.trim());
                const dateB = new Date(b.querySelector('.badge').textContent.trim());
                return dateA - dateB;
            } else if (sortBy.includes('price')) {
                const priceA = parseInt(a.querySelector('.text-primary').textContent.replace(/[^0-9]/g, ''));
                const priceB = parseInt(b.querySelector('.text-primary').textContent.replace(/[^0-9]/g, ''));
                return sortBy === 'price-asc' ? priceA - priceB : priceB - priceA;
            } else {
                const nameA = a.querySelector('.card-title').textContent.trim();
                const nameB = b.querySelector('.card-title').textContent.trim();
                return nameA.localeCompare(nameB);
            }
        });

        concerts.forEach(concert => {
            container.appendChild(concert.closest('.col-md-6, .col-12'));
        });
    }

    searchInput.addEventListener('input', filterConcerts);
    filterMonth.addEventListener('change', filterConcerts);
    filterGenre.addEventListener('change', filterConcerts);
    sortSelect.addEventListener('change', sortConcerts);

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection
