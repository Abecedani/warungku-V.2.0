@extends('layouts.main')

@section('title', 'Pesanan - WarungKu')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/penjual-dashboard.css') }}">

    <main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Pesanan Masuk</h4>
                <p class="text-muted small mb-0">{{ $warung->name }}</p>
            </div>
            <span class="badge bg-danger rounded-pill px-3 py-2">
                {{ $orders->where('status', 'pending')->count() }} Pending
            </span>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter Tab --}}
        @php
            $filter = request('status', 'semua');
            $statuses = ['semua', 'pending', 'dibayar', 'diproses', 'siap_diambil', 'selesai', 'dibatalkan'];
        @endphp
        <div class="d-flex gap-2 flex-wrap mb-4">
            @foreach($statuses as $s)
                <a href="{{ route('warungs.pesanan', ['status' => $s]) }}"
                    class="btn btn-sm rounded-pill {{ $filter === $s ? 'btn-orange' : 'btn-outline-secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                </a>
            @endforeach
        </div>

        {{-- Daftar Pesanan --}}
        @php
            $filtered = $filter === 'semua' ? $orders : $orders->where('status', $filter);
        @endphp

        @if($filtered->count() > 0)
            <div class="d-flex flex-column gap-3">
                @foreach($filtered as $order)
                    <div class="bg-white rounded-3 shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="fw-bold mb-0">#{{ $order->order_code }}</p>
                                <small class="text-muted">
                                    {{ $order->user->name }} &middot;
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                            @php
                                $badgeColor = match ($order->status) {
                                    'pending' => 'warning text-dark',
                                    'dibayar' => 'info text-dark',
                                    'diproses' => 'primary',
                                    'siap_diambil' => 'success',
                                    'selesai' => 'secondary',
                                    'dibatalkan' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} rounded-pill px-3">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            @foreach($order->orderItems as $item)
                                <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                                    {{-- Foto Menu --}}
                                    @php $img = $item->menu?->images->first()?->path; @endphp
                                    @if($img)
                                        <img src="{{ asset('storage/' . $img) }}" class="rounded-3"
                                            style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                            style="width: 60px; height: 60px; font-size: 1.5rem;">🍽️</div>
                                    @endif

                                    <div class="flex-grow-1">
                                        <p class="mb-0 small fw-bold">{{ $item->menu->name ?? 'Menu dihapus' }}</p>
                                        @if($item->variant)
                                            <small class="text-muted d-block">{{ $item->variant->name }}</small>
                                        @endif
                                        <small class="text-muted">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                    </div>
                                    <p class="mb-0 small fw-bold">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total & Aksi --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fw-bold mb-0">
                                Total: <span style="color: var(--orange);">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </p>

                            @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                                <form method="POST" action="{{ route('warungs.pesanan.status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="d-flex gap-2">
                                        <select name="status" class="form-select form-select-sm rounded-3">
                                            @foreach(['pending', 'dibayar', 'diproses', 'siap_diambil', 'selesai', 'dibatalkan'] as $s)
                                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                                    {{ ucfirst(str_replace('_', ' ', $s)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-orange rounded-3 px-3">Update</button>
                                    </div>
                                </form>
                            @endif
                        </div>

                        {{-- Ulasan --}}
@if($order->menuRatings->count() > 0)
    <div class="mt-3 p-3 rounded-3" style="background: #f9f9f9;">
        <p class="small fw-bold mb-2">Ulasan dari {{ $order->user->name }}:</p>
        @foreach($order->menuRatings as $rating)
            <div class="mb-2">
                <div style="color: #f59e0b; font-size: 0.85rem;">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $rating->rating ? '★' : '☆' }}
                    @endfor
                </div>
                @if($rating->review)
                    <p class="small text-muted mb-0">"{{ $rating->review }}"</p>
                @endif
            </div>
            @if(!$loop->last)
                <hr class="my-2">
            @endif
        @endforeach
    </div>
@endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3 shadow-sm p-5 text-center">
                <div style="font-size: 3rem;">📦</div>
                <h5 class="fw-bold mt-3">Belum ada pesanan</h5>
                <p class="text-muted mb-0">Pesanan akan muncul di sini ketika pembeli melakukan order.</p>
            </div>
        @endif

    </main>

@endsection