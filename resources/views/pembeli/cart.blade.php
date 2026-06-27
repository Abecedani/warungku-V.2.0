@extends('layouts.main')

@section('title', 'Keranjang - WarungKu')

@section('content')
<style>
    .btn-orange { background: #e65c00; color: white; }
    .btn-orange:hover { background: #cc5200; color: white; }
    .text-orange { color: #e65c00; }
    .qty-btn { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: white; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .qty-btn:hover { background: #f5f5f5; }
</style>

<div class="container py-5">
    <h4 class="fw-bold mb-4">🛒 Keranjang Belanja</h4>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    @if($cartItems->count() > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="bg-white rounded-3 shadow-sm p-4">
                    @if($warung)
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <span style="font-size: 1.5rem;">🏪</span>
                            <div>
                                <p class="fw-bold mb-0">{{ $warung->name }}</p>
                                <small class="text-muted">{{ $warung->location_detail }}</small>
                            </div>
                        </div>
                    @endif

                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                            @if($item->menu->image)
                                <img src="{{ Storage::url($item->menu->image) }}"
                                    class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                    style="width: 70px; height: 70px; font-size: 1.5rem;">🍽️</div>
                            @endif

                            <div class="flex-grow-1">
                                <p class="fw-bold mb-0">{{ $item->menu->name }}</p>
                                <small class="text-muted">Rp {{ number_format($item->menu->price, 0, ',', '.') }}</small>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <form method="POST" action="{{ route('cart.decrease', $item->menu) }}">
                                    @csrf
                                    <button class="qty-btn">−</button>
                                </form>
                                <span class="fw-bold px-2">{{ $item->quantity }}</span>
                                <form method="POST" action="{{ route('cart.add', $item->menu) }}">
                                    @csrf
                                    <button class="qty-btn">+</button>
                                </form>
                            </div>

                            <p class="fw-bold mb-0 text-orange" style="min-width: 80px; text-align: right;">
                                Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}
                            </p>

                            <form method="POST" action="{{ route('cart.remove', $item->menu) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-end mt-3">
                        <form method="POST" action="{{ route('cart.clear') }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-secondary rounded-3">
                                <i class="bi bi-trash me-1"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bg-white rounded-3 shadow-sm p-4 sticky-top" style="top: 80px;">
                    <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>

                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">{{ $item->menu->name }} x{{ $item->quantity }}</small>
                            <small>Rp {{ number_format($item->menu->price * $item->quantity, 0, ',', '.') }}</small>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-orange">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-orange w-100 rounded-3 fw-bold">
                        Lanjut Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3 shadow-sm p-5 text-center">
            <div style="font-size: 3rem;">🛒</div>
            <h5 class="fw-bold mt-3">Keranjang Kosong</h5>
            <p class="text-muted mb-4">Yuk cari menu favoritmu!</p>
            <a href="{{ route('home') }}" class="btn btn-orange rounded-3 px-4">Cari Warung</a>
        </div>
    @endif
</div>
@endsection