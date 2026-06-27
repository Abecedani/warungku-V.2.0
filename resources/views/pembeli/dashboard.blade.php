@extends('layouts.main')

@section('title', 'Dashboard Mahasiswa - WarungKu')

@section('content')

<div class="p-4" style="background: #fafafa; min-height: 100vh;">

    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-0">Dashboard Mahasiswa</h4>
        <p class="text-muted small mb-0">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>

    {{-- Welcome Card --}}
    <div class="bg-white rounded-3 shadow-sm p-4 mb-4 d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! 🎉</h5>
            <p class="text-muted mb-3">Cari menu favorit di kampus hari ini?</p>
            <a href="{{ route('home') }}" class="btn btn-orange rounded-3 px-4">
                <i class="bi bi-shop me-2"></i>Mulai Belanja
            </a>
        </div>
        <div style="font-size: 5rem; opacity: 0.15;">🍽️</div>
    </div>

    {{-- Quick Stats --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="mb-2" style="font-size: 1.8rem;">🛒</div>
                <p class="text-muted small text-uppercase mb-1">Keranjang</p>
                <p class="fw-bold mb-0" style="font-size: 1.5rem;">
                    {{ auth()->user()->carts()->count() }}
                </p>
                <a href="{{ route('cart.index') }}" class="text-decoration-none small" style="color: #e65c00;">
                    Lihat keranjang →
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="mb-2" style="font-size: 1.8rem;">📦</div>
                <p class="text-muted small text-uppercase mb-1">Total Pesanan</p>
                <p class="fw-bold mb-0" style="font-size: 1.5rem;">
                    {{ auth()->user()->orders()->count() }}
                </p>
                <span class="text-muted small">pesanan kamu</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="mb-2" style="font-size: 1.8rem;">⭐</div>
                <p class="text-muted small text-uppercase mb-1">Warung Favorit</p>
                <p class="fw-bold mb-0" style="font-size: 1.5rem;">—</p>
                <span class="text-muted small">segera hadir</span>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-3 shadow-sm p-4">
        <p class="fw-bold mb-3">⚡ Aksi Cepat</p>
        <div class="d-flex gap-3 flex-wrap">
            <a href="{{ route('home') }}" class="btn btn-orange rounded-3 px-4">
                <i class="bi bi-shop me-2"></i>Jelajahi Warung
            </a>
            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-3 px-4">
                <i class="bi bi-cart me-2"></i>Keranjang Saya
            </a>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary rounded-3 px-4">
                <i class="bi bi-person me-2"></i>Edit Profil
            </a>
        </div>
    </div>

</div>

@endsection