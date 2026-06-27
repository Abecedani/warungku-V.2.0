@extends('layouts.main')

@section('title', 'Beli Sekarang - WarungKu')

@section('content')
<style>
    .btn-orange { background: #e65c00; color: white; }
    .btn-orange:hover { background: #cc5200; color: white; }
    .text-orange { color: #e65c00; }
    .ewallet-option input[type="radio"] { display: none; }
    .ewallet-option label {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 16px; border: 2px solid #f0f0f0;
        border-radius: 12px; cursor: pointer; transition: 0.2s;
        font-weight: 500;
    }
    .ewallet-option input:checked + label {
        border-color: #e65c00;
        background: #fff3ec;
        color: #e65c00;
    }
</style>

<div class="container py-5">
    <h4 class="fw-bold mb-4">💳 Beli Sekarang</h4>

    <div class="row g-4">
        <div class="col-lg-7">

            {{-- Detail Menu --}}
            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">📦 Detail Pesanan</h6>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <p class="mb-0 fw-bold">{{ $menu->name }}</p>
                        @if($variant)
                            <small class="text-muted">Varian: {{ $variant->name }}</small><br>
                        @endif
                        <small class="text-muted">{{ $menu->warung->name }}</small>
                    </div>
                    <p class="mb-0 fw-bold text-orange">Rp {{ number_format($price, 0, ',', '.') }}</p>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold text-orange">Rp {{ number_format($price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Pilih E-Wallet --}}
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-3">💰 Pilih Metode Pembayaran</h6>
                <form method="POST" action="{{ route('checkout.process-now') }}">
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                    @if($variant)
                        <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                    @endif
                    <div class="d-flex flex-column gap-2 mb-4">
                        @foreach($ewallets as $wallet)
                            <div class="ewallet-option">
                                <input type="radio" name="payment_type" id="wallet_{{ $loop->index }}"
                                    value="{{ $wallet }}" {{ $loop->first ? 'checked' : '' }}>
                                <label for="wallet_{{ $loop->index }}">
                                    @if($wallet === 'GoPay') 💚
                                    @elseif($wallet === 'OVO') 💜
                                    @elseif($wallet === 'Dana') 💙
                                    @elseif($wallet === 'ShopeePay') 🧡
                                    @else 💛
                                    @endif
                                    {{ $wallet }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-orange w-100 rounded-3 fw-bold py-3">
                        Bayar Sekarang — Rp {{ number_format($price, 0, ',', '.') }}
                    </button>
                </form>
            </div>

        </div>

        <div class="col-lg-5">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-3">ℹ️ Info Pembayaran</h6>
                <p class="text-muted small">Pilih metode e-wallet lalu klik bayar. Pesanan akan langsung diproses oleh warung.</p>
                <div class="d-flex align-items-center gap-2 p-3 rounded-3" style="background: #fff3ec;">
                    <span>🔒</span>
                    <small class="fw-bold" style="color: #e65c00;">Transaksi Aman & Terpercaya</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection