<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Tiket Konser</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>
    <header class="main-header">
        <h1>Sistem Penjualan Tiket Konser</h1>
        <nav aria-label="Main Navigation">
            <ul class="nav-list">
                <li><a href="{{ route('landing') }}" class="nav-link">Beranda</a></li>
                @if(Auth::check())
                    <li><a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a></li>
                    <li><form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-link">Logout</button>
                    </form></li>
                @else
                    <li><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                @endif
            </ul>
        </nav>
    </header>

    <main class="container">
        <h2>Daftar Konser</h2>

        <!-- Menampilkan daftar konser secara dinamis -->
        @foreach($concerts as $concert)
        <div class="card">
            <h3>{{ $concert->name }}</h3>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }}</p>
            <p><strong>Lokasi:</strong> {{ $concert->location }}</p>
            <a href="{{ route('concerts.show', $concert->id) }}" class="btn">Detail</a>
        </div>
        @endforeach
    </main>

    <footer class="main-footer">
        <p>&copy; 2024 Sistem Penjualan Tiket Konser. Semua Hak Dilindungi.</p>
    </footer>
</body>
</html>
