<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konser Musik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            color: #fff;
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-image: url('https://source.unsplash.com/1600x900/?concert,music');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }
        .hero-content {
            position: relative;
            z-index: 1;
        }
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .concert-card {
            margin-bottom: 2rem;
        }
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 1rem 0;
            text-align: center;
        }
        .btn-primary-lg {
            padding: 10px 30px;
            font-size: 1.25rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Konser Musik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#concerts">Konser</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content text-white">
            <h1>Selamat Datang di Konser Musik Terbesar</h1>
            <p>Pesan tiket konser impianmu sekarang dan nikmati pengalaman tak terlupakan!</p>

            <!-- Tombol Daftar dan Login -->
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Daftar Sekarang</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg ms-3">Login</a>
        </div>
    </div>

    <!-- Concerts Section -->
    <section id="concerts" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Konser Mendatang</h2>
            <div class="row">
                <!-- Menampilkan konser -->
                @foreach ($concerts as $concert)
                <div class="col-md-4">
                    <div class="card concert-card">
                        <img src="{{ $concert->image_url }}" class="card-img-top" alt="{{ $concert->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $concert->title }}</h5>
                            <p class="card-text">{{ $concert->description }}</p>
                            <p class="card-text"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($concert->date)->format('d F Y') }}</p>
                            <p class="card-text"><strong>Waktu:</strong> {{ $concert->time }}</p>
                            <p class="card-text"><strong>Harga:</strong> Rp{{ number_format($concert->price, 0, ',', '.') }}</p>
                            <a href="{{ route('concert.show', $concert->id) }}" class="btn btn-primary">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 text-center">
        <div class="container">
            <h2 class="mb-4">Hubungi Kami</h2>
            <p>Email: <a href="mailto:info@konsermusik.com">info@konsermusik.com</a></p>
            <p>Telepon: +62 xxxxxxx</p>
            <p>Alamat: Jl. Maguwoharjo No. 123, Yogyakarta</p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Konser Musik. Semua Hak Dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
