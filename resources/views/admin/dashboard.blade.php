@extends('layouts.admin')

@section('title', 'Dashboard Admin - WarungKu')

@section('content')
<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Dashboard Admin</h4>
            <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }}</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    {{-- Stat Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">👤</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $totalUsers }}</h5>
                <small class="text-muted">Total Pengguna</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">🏪</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $totalWarungs }}</h5>
                <small class="text-muted">Warung Aktif</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">⏳</div>
                <h5 class="fw-bold mt-2 mb-0">{{ $pendingWarungs }}</h5>
                <small class="text-muted">Menunggu Verifikasi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-white rounded-3 shadow-sm p-4 text-center">
                <div style="font-size: 2rem;">💰</div>
                <h5 class="fw-bold mt-2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                <small class="text-muted">Total Transaksi</small>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Warung Pending --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Warung Menunggu Verifikasi</h6>
                    <a href="{{ route('admin.warungs') }}" class="small" style="color: #e65c00;">Lihat semua</a>
                </div>
                @forelse($warungsNeedVerify as $warung)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <p class="fw-bold small mb-0">{{ $warung->name }}</p>
                            <small class="text-muted">{{ $warung->user->name }}</small>
                        </div>
                        <form method="POST" action="{{ route('admin.warungs.verify', $warung->id) }}">
                            @csrf
                            <button class="btn btn-sm btn-success rounded-3">Verifikasi</button>
                        </form>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Tidak ada warung yang menunggu verifikasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Pesanan Terbaru --}}
        <div class="col-md-6">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Pesanan Terbaru</h6>
                    <a href="{{ route('admin.transaksi') }}" class="small" style="color: #e65c00;">Lihat semua</a>
                </div>
                @forelse($recentOrders as $order)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <p class="fw-bold small mb-0">#{{ $order->order_code }}</p>
                            <small class="text-muted">{{ $order->user->name }} · {{ $order->warung->name }}</small>
                        </div>
                        <span class="badge bg-secondary rounded-pill">{{ ucfirst($order->status) }}</span>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>
    </div>

</main>
@endsection