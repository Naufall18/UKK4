<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital — Sekolah Digital</title>
    <meta name="description"
        content="Sistem perpustakaan digital sekolah. Pinjam buku, kelola koleksi, dan pantau riwayat peminjaman secara online.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ── Navbar ── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 16px 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            padding: 10px 0;
        }

        .navbar-inner {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #1e293b;
            font-weight: 700;
            font-size: 18px;
        }

        .navbar-brand .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .navbar-brand .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-links a {
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-ghost {
            color: #475569;
        }

        .btn-ghost:hover {
            color: #1e293b;
            background: rgba(0, 0, 0, 0.04);
        }

        .btn-solid {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
        }

        .btn-solid:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        /* ── Hero Section ── */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.06) 0%, transparent 70%);
            bottom: -50px;
            left: -50px;
            border-radius: 50%;
        }

        .hero-inner {
            max-width: 1140px;
            margin: 0 auto;
            padding: 120px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-text h1 {
            font-size: 48px;
            font-weight: 800;
            line-height: 1.15;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, #4f46e5, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 17px;
            line-height: 1.7;
            color: #64748b;
            margin-bottom: 32px;
            max-width: 480px;
        }

        .hero-buttons {
            display: flex;
            gap: 12px;
        }

        .hero-buttons a {
            text-decoration: none;
            padding: 13px 28px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.25s ease;
        }

        .hero-buttons .btn-primary-lg {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        .hero-buttons .btn-primary-lg:hover {
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
            transform: translateY(-2px);
        }

        .hero-buttons .btn-outline-lg {
            background: transparent;
            color: #4f46e5;
            border: 1.5px solid #c7d2fe;
        }

        .hero-buttons .btn-outline-lg:hover {
            background: #eef2ff;
            border-color: #a5b4fc;
        }

        /* Hero visual */
        .hero-visual {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-card-stack {
            position: relative;
            width: 380px;
            height: 340px;
        }

        .float-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
            animation: floatUp 6s ease-in-out infinite;
        }

        .float-card-1 {
            top: 0;
            left: 0;
            animation-delay: 0s;
        }

        .float-card-2 {
            top: 40px;
            right: 0;
            animation-delay: 2s;
        }

        .float-card-3 {
            bottom: 0;
            left: 30px;
            animation-delay: 4s;
        }

        .float-card .fc-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            margin-bottom: 12px;
        }

        .float-card h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .float-card p {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }

        @keyframes floatUp {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        /* ── Features Section ── */
        .features {
            padding: 80px 24px;
            max-width: 1140px;
            margin: 0 auto;
        }

        .features-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .features-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .features-header p {
            font-size: 16px;
            color: #64748b;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            padding: 32px 28px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
            border-color: rgba(99, 102, 241, 0.15);
        }

        .feature-card .fc-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
            margin: 0;
        }

        /* ── Footer ── */
        .landing-footer {
            text-align: center;
            padding: 32px 24px;
            color: #94a3b8;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
        }

        /* ── Fade-in animation ── */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUp 0.7s ease forwards;
        }

        .fade-up-d1 {
            animation-delay: 0.1s;
        }

        .fade-up-d2 {
            animation-delay: 0.25s;
        }

        .fade-up-d3 {
            animation-delay: 0.4s;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .hero-inner {
                grid-template-columns: 1fr;
                text-align: center;
                padding-top: 100px;
            }

            .hero-text h1 {
                font-size: 32px;
            }

            .hero-text p {
                max-width: 100%;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-visual {
                display: none;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons a {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-brand">
                <div class="brand-icon"><img src="{{ asset('img/logo.png') }}" alt="Logo"></div>
                Perpustakaan
            </a>
            <div class="navbar-links">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/home') }}" class="btn-solid">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-solid">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-text fade-up">
                <h1>Perpustakaan <span>Digital</span> Sekolah</h1>
                <p>Akses ribuan koleksi buku, lakukan peminjaman, dan pantau riwayat bacaanmu — semuanya dalam satu
                    platform yang mudah dan cepat.</p>
                <div class="hero-buttons">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="btn-primary-lg">
                                <i class="bi bi-grid-1x2 me-1"></i> Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary-lg">Mulai Sekarang</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-outline-lg">Daftar Akun</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
            <div class="hero-visual fade-up fade-up-d2">
                <div class="hero-card-stack">
                    <div class="float-card float-card-1">
                        <div class="fc-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <h4>1.250+ Koleksi</h4>
                        <p>Buku tersedia di perpustakaan</p>
                    </div>
                    <div class="float-card float-card-2">
                        <div class="fc-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h4>Peminjaman Mudah</h4>
                        <p>Pinjam buku hanya dalam 1 klik</p>
                    </div>
                    <div class="float-card float-card-3">
                        <div class="fc-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h4>Riwayat Online</h4>
                        <p>Pantau semua aktivitas bacamu</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="features-header fade-up">
            <h2>Kenapa Menggunakan Platform Ini?</h2>
            <p>Fitur yang dirancang untuk memudahkan pengalaman membaca di sekolah</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-up fade-up-d1">
                <div class="fc-icon" style="background: linear-gradient(135deg, #6366f1, #818cf8);">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Pencarian Cepat</h3>
                <p>Temukan buku yang kamu cari dalam hitungan detik dengan fitur pencarian yang canggih dan responsif.
                </p>
            </div>
            <div class="feature-card fade-up fade-up-d2">
                <div class="fc-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <i class="bi bi-bookmark-check-fill"></i>
                </div>
                <h3>Peminjaman Online</h3>
                <p>Tidak perlu antri. Pilih buku, klik pinjam, dan buku siap diambil. Proses peminjaman semudah belanja
                    online.</p>
            </div>
            <div class="feature-card fade-up fade-up-d3">
                <div class="fc-icon" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3>Dashboard Personal</h3>
                <p>Lihat statistik bacaanmu, pantau buku yang sedang dipinjam, dan kelola denda secara transparan.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <p>&copy; {{ date('Y') }} Perpustakaan Digital — Sekolah Digital. Dibangun dengan <i class="bi bi-heart-fill"
                style="color: #e11d48; font-size: 11px;"></i></p>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Intersection Observer for fade-up animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-up').forEach(el => {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>

</html>