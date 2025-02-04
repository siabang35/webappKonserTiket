<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MUSICxASIX</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        main {
            flex: 1;
        }
        /* Offcanvas Menu */
        .offcanvas {
    background: linear-gradient(135deg, rgba(65, 88, 208, 0.8) 0%, rgba(200, 80, 192, 0.8) 100%);
    color: white;
    backdrop-filter: blur(10px); /* Efek blur pada background */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Bayangan untuk kedalaman */
    border-radius: 0.75rem; /* Rounded corners */
    padding: 1rem;
}

.offcanvas-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.offcanvas-title {
    font-size: 1.5rem;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px; /* Memberikan kesan premium */
    color: rgba(255, 255, 255, 0.9);
}

        .navbar {
            background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
            z-index: 2; /* Pastikan tombol berada di atas elemen Selamat Datang */
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        .navbar-brand {
            font-weight: 700;
            color: #fff;
            font-size: 1.5rem;
        }
     /* Sidebar Links */
/* Links in Sidebar */
.offcanvas .nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem; /* Spasi antara ikon dan teks */
    color: rgba(255, 255, 255, 0.85); /* Warna putih dengan transparansi */
    font-weight: 500;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease, transform 0.2s ease;
    text-decoration: none;
    background-color: transparent;
}

.offcanvas .nav-link:hover {
    transform: scale(1.05); /* Efek pembesaran saat hover */
    background-color: rgba(255, 255, 255, 0.15); /* Efek hover lembut */
    color: #ffffff;
    transform: translateX(5px); /* Sedikit animasi */
}

.offcanvas .nav-link.active {
    background-color: rgba(255, 255, 255, 0.25); /* Warna khusus untuk menu aktif */
    color: #fff;
    font-weight: bold;
}

/* Button Logout */
.offcanvas .btn-logout {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #fff;
    background: linear-gradient(135deg, rgba(255, 105, 180, 0.8), rgba(65, 88, 208, 0.8));
    border: none;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease, transform 0.2s ease;
    cursor: pointer;
    font-weight: 500;
    text-decoration: none;
}

.offcanvas .btn-logout:hover {
    background-color: rgba(255, 255, 255, 0.2); /* Efek hover transparan */
    transform: translateY(-3px); /* Sedikit animasi naik */
}

/* Responsif */
@media (max-width: 768px) {
    .offcanvas {
        padding: 1rem 0.5rem;
    }
    .offcanvas .nav-link {
        font-size: 1rem; /* Font lebih besar */
    }
    .offcanvas-title {
        font-size: 1.25rem;
    }
}
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
        }
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
        footer {
            background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
            color: #fff;
            padding: 2rem 0;
        }
        .card {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .navbar-collapse {
                background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
                border-radius: 1rem;
                padding: 1rem;
                margin-top: 1rem;
            }
            .nav-link {
                text-align: center;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header (Navigation Bar) -->
    <header class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">🎵 MUSICxASIX</a>

        <!-- Tombol Toggle untuk Offcanvas -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Offcanvas Navigasi -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">🎵 MUSICxASIX</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="nav-link">Register</a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                        </li>
                            <li class="nav-item">
                                <a href="{{ route('tickets.index') }}" class="nav-link">
                                    <i class="bi bi-ticket-perforated"></i> Tiket Saya
                                </a>
                            </li>
                        <!-- Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">Profil</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('order.history') }}">Riwayat Pesanan</a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</header>




    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="mb-3">MUSICxASIX</h5>
                    <p class="mb-0">Nikmati pengalaman konser terbaik bersama kami</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                    <p class="mt-3 mb-0">&copy; 2025 #Sistem Penjualan Tiket Konser</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inisialisasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Animasi scroll smooth untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Tambahkan class active pada navbar saat scroll
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('shadow-lg');
            } else {
                document.querySelector('.navbar').classList.remove('shadow-lg');
            }
        });
    </script>
</body>
</html>
