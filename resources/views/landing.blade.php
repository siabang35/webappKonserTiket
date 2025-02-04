<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MUSICxASIX') }} - Platform Konser Terbaik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Add Swiper.js CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"></style>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #7c3aed;
            --accent-color: #06b6d4;
            --dark-blue: #1e3a8a;
            --text-light: #f3f4f6;
            --gradient-start: #3b82f6;
            --gradient-mid: #8b5cf6;
            --gradient-end: #06b6d4;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --glass-shadow: rgba(0, 0, 0, 0.1);
        }

        /* Base Styles */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1b4b, #312e81);
            color: var(--text-light);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Enhanced Navbar */
        .navbar {
            background: linear-gradient(135deg,
                rgba(65, 88, 208, 0.85) 0%,
                rgba(124, 58, 237, 0.85) 50%,
                rgba(200, 80, 192, 0.85) 100%
            );
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar.scrolled {
            background: linear-gradient(135deg,
                rgba(65, 88, 208, 0.95) 0%,
                rgba(124, 58, 237, 0.95) 50%,
                rgba(200, 80, 192, 0.95) 100%
            );
            padding: 0.5rem 0;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }

        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--gradient-start), transparent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover::after {
            transform: scaleX(1);
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-light) !important;
            position: relative;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .nav-link:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        /* Enhanced Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(30, 27, 75, 0.9)),
                        url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center,
                rgba(124, 58, 237, 0.1) 0%,
                rgba(15, 23, 42, 0.2) 100%
            );
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 2rem;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Enhanced Concert Cards */
        .concert-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(6, 182, 212, 0.1);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .concert-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(59, 130, 246, 0.1),
                rgba(6, 182, 212, 0.1)
            );
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .concert-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
            border-color: rgba(6, 182, 212, 0.3);
        }

        .concert-card:hover::before {
            opacity: 1;
        }

        .concert-card img {
            height: 60px;
            width: 40px;
            object-fit: cover;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .concert-card:hover img {
            transform: scale(1.1);
            filter: brightness(1.1);
        }

        .card-body {
            padding: 2rem;
            background: linear-gradient(135deg,
                rgba(15, 23, 42, 0.95) 0%,
                rgba(30, 27, 75, 0.95) 100%
            );
            border-top: 1px solid rgba(6, 182, 212, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .price-tag {
            background: linear-gradient(45deg,
                var(--gradient-start),
                var(--gradient-end)
            );
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.2);
            color: white;
        }

/* Featured Artists Section Styling */
.featured-artists {
    background: linear-gradient(to bottom, rgba(15, 23, 42, 0.95), rgba(30, 27, 75, 0.95));
    padding: 4rem 0;
    position: relative;
}

.featured-artists h2 {
    margin-bottom: 2rem;
    color: #f8fafc;
}

/* Swiper Slide and Artist Card */
.artist-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    background: #1e293b;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.artist-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
}

/* Image Container */
.image-container {
    width: 180px;
    height: 180px;
    overflow: hidden;
    border-radius: 50%;
    margin-bottom: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.image-container img:hover {
    transform: scale(1.05);
}

/* Artist Info */
.artist-info h3 {
    margin: 0.5rem 0;
    font-size: 1rem;
    color: #f1f5f9;
}

.artist-info p {
    font-size: 0.9rem;
    color: #cbd5e1;
}



        /* Enhanced Buttons */
        .btn-gradient {
            background: linear-gradient(45deg,
                var(--gradient-start),
                var(--gradient-end)
            );
            border: none;
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg,
                var(--gradient-end),
                var(--gradient-start)
            );
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        .btn-gradient:hover::before {
            opacity: 1;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--gradient-end);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-outline::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg,
                var(--gradient-start),
                var(--gradient-end)
            );
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .btn-outline:hover {
            color: white;
            border-color: transparent;
        }

        .btn-outline:hover::before {
            opacity: 1;
        }

        /* Enhanced Modal */
        .modal-content {
            background: linear-gradient(135deg,
                rgba(15, 23, 42, 0.95) 0%,
                rgba(30, 27, 75, 0.95) 100%
            );
            border: 1px solid rgba(6, 182, 212, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
        }

        .modal-header {
            border-bottom: 1px solid rgba(6, 182, 212, 0.2);
            padding: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-title {
            background: linear-gradient(45deg, #fff, #e2e8f0);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.6s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .btn-gradient,
            .btn-outline {
                padding: 0.8rem 1.5rem;
            }

            .concert-card {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">MUSICxASIX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#concerts">Event</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#artists">Artis</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link btn btn-outline">Masuk</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="{{ route('register') }}" class="nav-link btn btn-gradient">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-outline">Keluar</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <h1>Temukan Event Musik Terbaik</h1>
                <p class="lead">Jelajahi ribuan konser dan festival musik dari artis favoritmu. Pesan tiket dengan mudah dan aman.</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('register') }}" class="btn btn-gradient">Mulai Sekarang</a>
                    <a href="#concerts" class="btn btn-outline">Lihat Event</a>
                </div>
            </div>
        </div>
    </section>

<!-- Concerts Section -->
<section id="concerts" class="py-5 mt-10">
    <div class="container">
        <h2 class="section-title text-center mb-4" data-aos="fade-up">Event Mendatang</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-4">
            @foreach($concerts as $concert)
                <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="card concert-card h-100">
                        <!-- Gambar Konser berdasarkan artis -->
                        <img src="{{ $concert->ticket_image ?? asset('assets/images/artists/default_ticket.jpg') }}"
                             class="card-img-top"
                             alt="{{ $concert->title }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $concert->title }}</h5>
                            <p class="card-text text-light">{{ Str::limit($concert->description, 100) }}</p>
                            <div class="price-tag mb-3">
                                Rp{{ number_format($concert->price, 0, ',', '.') }}
                            </div>
                            <div class="mt-auto">
                                <p class="mb-1"><small>📅 {{ $concert->formatted_date }}</small></p>
                                <p><small>⏰ {{ $concert->time }}</small></p>
                            </div>
                            <button type="button"
                                    class="btn btn-gradient w-100 mt-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ticketModal{{ $concert->id }}">
                                Pesan Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Ticket Modal -->
                <div class="modal fade"
                     id="ticketModal{{ $concert->id }}"
                     tabindex="-1"
                     aria-labelledby="ticketModalLabel{{ $concert->id }}"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="ticketModalLabel{{ $concert->id }}">
                                    {{ $concert->title }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Display the concert ticket image -->
                                <img src="{{ $concert->ticket_image ?? asset('assets/images/artists/default_ticket.jpg') }}"
                                     class="img-fluid mb-4"
                                     alt="Ticket for {{ $concert->title }}">
                                <p class="text-light">{{ $concert->description }}</p>

                                <div class="info-grid mt-4">
                                    <div class="info-item mb-2">
                                        <strong class="text-light">📅 Tanggal:</strong>
                                        <p class="text-light">{{ $concert->formatted_date }}</p>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong class="text-light">⏰ Waktu:</strong>
                                        <p class="text-light">{{ $concert->time }}</p>
                                    </div>
                                    <div class="info-item mb-2">
                                        <strong class="text-light">📍 Venue:</strong>
                                        <p class="text-light">{{ $concert->venue }}</p>
                                    </div>
                                </div>

                                <hr class="border-light">
                                <h6 class="text-light mb-4">Pilih Tiket</h6>
                                <div class="d-flex justify-content-between gap-3">
                                    <a href="{{ route('ticket.purchase', ['concert' => $concert->id, 'type' => 'vip']) }}"
                                       class="btn btn-gradient flex-grow-1">VIP</a>
                                    <a href="{{ route('ticket.purchase', ['concert' => $concert->id, 'type' => 'regular']) }}"
                                       class="btn btn-outline flex-grow-1">Reguler</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>






<!-- Featured Artists Section -->
<section id="artists" class="py-6">
<section class="featured-artists py-20 bg-black/95 relative overflow-hidden">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold text-center text-white mb-12">
            Featured Artists
        </h2>

        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Generate Slides Dynamically from Featured Artists -->
                @foreach($featuredArtists as $artist)
                <div class="swiper-slide">
                    <div class="artist-card">
                        <div class="image-container">
                            <img src="{{ $artist['image'] }}" alt="{{ $artist['name'] }}" />
                        </div>
                        <div class="artist-info text-center">
                            <h3 class="font-semibold text-lg">{{ $artist['name'] }}</h3>
                            <p class="text-sm text-gray-300">{{ $artist['genre'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>




    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5" data-aos="fade-up">Hubungi Kami</h2>
            <div class="row justify-content-center g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info p-4 text-center">
                        <h4>📧 Email</h4>
                        <p><a href="mailto:info@musicxasix.com" class="text-light text-decoration-none">info@musicxasix.com</a></p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info p-4 text-center">
                        <h4>📱 Telepon</h4>
                        <p>+62 8xxxxxxxx</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-info p-4 text-center">
                        <h4>📍 Alamat</h4>
                        <p>Jl. Maguwoharjo No. 123, Yogyakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 text-center">
        <div class="container">
            <p>&copy; {{ date('Y') }} MUSICxASIX. Semua Hak Dilindungi.</p>
        </div>
    </footer>

  <!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>


<!-- Swiper.js JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Initialize Swiper
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 10,
            autoplay: {
                delay: 3000, // Delay between slides
                disableOnInteraction: false,
            },
            loop: true, // Enable infinite loop
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>

</body>
</html>
