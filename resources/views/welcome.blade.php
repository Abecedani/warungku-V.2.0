@extends('layouts.main')

@section('title', 'WarungKu')

@section('content')
    <style>
        /* === VARIABLES === */
        :root {
            --orange-primary: #e65c00;
            --orange-light: #F9D423;
            --dark-bg: #1a1a1a;
        }

        /* === HERO === */
        .hero-section {
            background: linear-gradient(135deg, var(--orange-primary) 0%, var(--orange-light) 100%);
            color: white;
            padding: 100px 0 80px;
        }

        /* === STATS === */
        .stats-bar {
            background-color: var(--dark-bg);
            padding: 30px 0;
        }

        /* === CARDS === */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* === UTILITIES === */
        .text-orange {
            color: var(--orange-primary);
        }

        .bg-orange {
            background-color: var(--orange-primary);
            color: white;
        }

        /* === CTA BANNER === */
        .cta-banner {
            background-color: var(--orange-primary);
            padding: 3rem 1rem;
            text-align: center;
            color: white;
        }

        .cta-banner .btn-cta-primary {
            background-color: white;
            color: var(--orange-primary);
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
        }

        .cta-banner .btn-cta-outline {
            border: 1px solid white;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
        }
    </style>

    {{-- ===== HERO ===== --}}
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">
                        Lapar di Kampus?<br>Temukan Warungmu di Sini!
                    </h1>
                    <p class="lead mb-4">
                        Direktori warung makan dan kantin di lingkungan Universitas Mataram.
                        Cari menu favorit, cek harga, dan langsung pesan — semuanya dalam satu platform.
                    </p>

                    <div class="input-group input-group-lg mb-4 shadow-sm" style="max-width: 600px;">
                        <span class="input-group-text bg-white border-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-0" placeholder="Cari warung atau menu...">
                        <button class="btn text-white px-4 fw-bold" style="background-color: var(--dark-bg);" type="button">
                            Cari
                        </button>
                    </div>

                    <div class="d-flex gap-4 fw-medium small">
                        <span><i class="bi bi-cup-hot me-1"></i> Makanan</span>
                        <span><i class="bi bi-cup-straw me-1"></i> Minuman</span>
                        <span><i class="bi bi-bag me-1"></i> Snack</span>
                        <span><i class="bi bi-tags me-1"></i> Paket Hemat</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== STATS ===== --}}
    <section class="stats-bar">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4 py-2">
                    <h2 class="fw-bold mb-0" style="color: var(--orange-light);">{{ $warungCount }}</h2>
                    <small class="text-white-50 text-uppercase">Warung Terdaftar</small>
                </div>
                <div class="col-md-4 py-2 border-start border-end border-secondary">
                    <h2 class="fw-bold mb-0" style="color: var(--orange-light);">{{ $menuCount }}</h2>
                    <small class="text-white-50 text-uppercase">Menu Tersedia</small>
                </div>
                <div class="col-md-4 py-2">
                    <h2 class="fw-bold mb-0" style="color: var(--orange-light);">{{ $mahasiswaCount }}</h2>
                    <small class="text-white-50 text-uppercase">Pengguna Aktif</small>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FITUR ===== --}}
    <section class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kenapa Pakai WarungKu?</h2>
            <p class="text-muted">Platform yang ngerti kebutuhan mahasiswa</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4">
                    <div class="fs-1 mb-3">🔍</div>
                    <h5 class="fw-bold">Cari dengan Mudah</h5>
                    <p class="text-muted small">Temukan warung dan menu favorit kamu dalam hitungan detik. Filter by
                        kategori, harga, atau rating.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4">
                    <div class="fs-1 mb-3">⚡</div>
                    <h5 class="fw-bold">Info Real-time</h5>
                    <p class="text-muted small">Cek status buka/tutup warung, estimasi waktu, dan ketersediaan menu sebelum
                        datang.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-4">
                    <div class="fs-1 mb-3">🛡️</div>
                    <h5 class="fw-bold">Terverifikasi</h5>
                    <p class="text-muted small">Semua warung sudah diverifikasi pengelola kampus. Aman, higienis, dan
                        terpercaya.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== PROMO ===== --}}
    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">🔥 Promo Hari Ini</h3>
                <p class="text-muted small mb-0">Jangan sampai kelewatan diskon khusus mahasiswa</p>
            </div>
            <a href="#" class="text-orange text-decoration-none fw-bold">Lihat Semua Promo &rsaquo;</a>
        </div>

        <div class="row g-4">
            @forelse ($menuPromo as $promo)
                <div class="col-md-3">
                    <a href="{{ route('warung.show', $promo->warung) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm card-hover position-relative">
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                Diskon 20%
                            </span>
                            @php $promoImg = $promo->images->first()?->path; @endphp
                            <img src="{{ $promoImg ? asset('storage/' . $promoImg) : 'https://placehold.co/400x300/F9D423/333333?text=' . urlencode($promo->name) }}"
                                class="card-img-top object-fit-cover" height="180" alt="{{ $promo->name }}">
                            <div class="card-body">
                                <small class="text-muted">{{ $promo->warung->name ?? 'Warung Unram' }}</small>
                                <h6 class="card-title fw-bold mt-1 mb-2 text-dark">{{ $promo->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted text-decoration-line-through small">
                                        Rp {{ number_format($promo->price + 5000, 0, ',', '.') }}
                                    </span>
                                    <span class="text-orange fw-bold">
                                        Rp {{ number_format($promo->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Belum ada menu promo hari ini.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===== POPULER ===== --}}
    <section class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Menu Paling Populer</h3>
                <p class="text-muted small mb-0">Sering banget dipesan sama anak-anak Unram</p>
            </div>
            <a href="#" class="text-orange text-decoration-none fw-bold">Eksplor Menu &rsaquo;</a>
        </div>

        <div class="row g-4">
            @forelse ($menuPopuler as $menu)
                <div class="col-md-3">
                    <a href="{{ route('warung.show', $menu->warung) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            @php $menuImg = $menu->images->first()?->path; @endphp
                            <img src="{{ $menuImg ? asset('storage/' . $menuImg) : 'https://placehold.co/400x300/EFEFEF/333333?text=' . urlencode($menu->name) }}"
                                class="card-img-top object-fit-cover" height="180" alt="{{ $menu->name }}">
                            <div class="card-body">
                                <small class="text-muted">{{ $menu->warung->name ?? 'Warung Unram' }}</small>
                                <h6 class="card-title fw-bold mt-1 mb-2 text-dark">{{ $menu->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                    <span class="text-warning small fw-bold">
                                        ★
                                        {{ $menu->ratings->count() > 0 ? number_format($menu->ratings->avg('rating'), 1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    <p>Belum ada menu yang terdaftar.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ===== CTA (Guest Only) ===== --}}
    @guest
        <section class="cta-banner">
            <h2 class="fw-bold mb-2">Punya Warung di Unram?</h2>
            <p class="mb-4">Daftarkan warungmu sekarang dan jangkau lebih banyak pelanggan. Gratis, gampang, dan langsung
                online!</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('warungs.create') }}" class="btn-cta-primary">Daftarkan Warung</a>
                <a href="{{ route('register') }}" class="btn-cta-outline">Daftar Sekarang</a>
            </div>
        </section>
    @endguest

    {{-- ===== FOOTER ===== --}}
    <footer class="py-4" style="background-color: var(--dark-bg);">
        <div class="container text-center">
            <div class="d-flex justify-content-center align-items-center gap-2 mb-2">
                <img src="{{ asset('assets/WarungKu-Logo.png') }}" alt="Logo WarungKu" width="24">
                <strong class="text-white">WarungKu</strong>
            </div>
            <p class="text-white-50 small mb-0">
                &copy; 2026 WarungKu &middot; Platform direktori warung Universitas Mataram
            </p>
        </div>
    </footer>

@endsection