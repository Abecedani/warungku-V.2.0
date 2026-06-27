@use('App\Models\MenuRating')
@extends('layouts.main')

@section('title', 'Pesanan Saya - WarungKu')

@section('content')
<style>
    .btn-orange { background: #e65c00; color: white; }
    .btn-orange:hover { background: #cc5200; color: white; }
    .text-orange { color: #e65c00; }
    .star-rating { display: flex; gap: 4px; flex-direction: row-reverse; justify-content: flex-end; }
    .star-rating input { display: none; }
    .star-rating label { font-size: 1.8rem; color: #ddd; cursor: pointer; transition: 0.2s; }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #f59e0b; }
    .star-display { color: #f59e0b; font-size: 1rem; }
</style>

<div class="container py-5">
    <h4 class="fw-bold mb-4">📦 Pesanan Saya</h4>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">{{ session('error') }}</div>
    @endif

    @if($orders->count() > 0)
        <div class="d-flex flex-column gap-4">
            @foreach($orders as $order)

                @php
                    $sudahRating = MenuRating::where('order_id', $order->id)->where('user_id', auth()->id())->exists();
                @endphp

                <div class="bg-white rounded-3 shadow-sm p-4">

                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="fw-bold mb-0">#{{ $order->order_code }}</p>
                            <small class="text-muted">
                                {{ $order->warung->name }} &middot;
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>
                        @php
                            $badgeColor = match($order->status) {
                                'pending'      => 'warning text-dark',
                                'dibayar'      => 'info text-dark',
                                'diproses'     => 'primary',
                                'siap_diambil' => 'success',
                                'selesai'      => 'secondary',
                                'dibatalkan'   => 'danger',
                                default        => 'secondary'
                            };
                        @endphp
                        <span class="badge bg-{{ $badgeColor }} rounded-pill px-3">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    {{-- Items --}}
                    @foreach($order->orderItems as $item)
                        <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                            @php $img = $item->menu?->images->first()?->path; @endphp
                            @if($img)
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="rounded-3"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                    style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    🍽️
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <p class="mb-0 small fw-bold">
                                    {{ $item->menu->name ?? 'Menu dihapus' }}
                                </p>
                                @if($item->variant)
                                    <small class="text-muted">{{ $item->variant->name }}</small><br>
                                @endif
                                <small class="text-muted">
                                    {{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </small>
                            </div>
                            <p class="mb-0 small fw-bold">
                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach

                    {{-- Footer --}}
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Dibayar via {{ $order->payment_type }}</small>
                        <p class="fw-bold mb-0">
                            Total: <span class="text-orange">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </p>
                    </div>

                    {{-- Rating --}}
                    @if($order->status === 'selesai')
                        @if($sudahRating)
                            <div class="mt-3 p-3 rounded-3" style="background: #f9f9f9;">
                                <p class="small fw-bold mb-2">Ulasan kamu:</p>
                                @foreach($order->menuRatings as $rating)
                                    <div class="mb-2">
                                        <p class="small fw-bold mb-0">
                                            {{ $rating->menu->name ?? 'Menu dihapus' }}
                                        </p>
                                        <div style="color: #f59e0b; font-size: 0.85rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $rating->rating ? '★' : '☆' }}
                                            @endfor
                                        </div>
                                        @if($rating->review)
                                            <p class="small text-muted mb-0">
                                                "{{ $rating->review }}"
                                            </p>
                                        @endif
                                    </div>
                                    @if(!$loop->last)
                                        <hr class="my-2">
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <button class="btn btn-sm btn-orange rounded-3 mt-3 px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRating-{{ $order->id }}">
                                ⭐ Beri Ulasan
                            </button>
                        @endif
                    @endif

                </div>

                {{-- Modal Rating --}}
                @if($order->status === 'selesai' && !$sudahRating)
                    <div class="modal fade" id="modalRating-{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-3 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h6 class="modal-title fw-bold">⭐ Ulasan untuk {{ $order->warung->name }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('orders.rating', $order) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-muted small mb-3">Bagaimana menu yang kamu pesan?</p>
                                        @foreach($order->orderItems as $item)
                                            @if($item->menu)
                                                <div class="mb-4">
                                                    <p class="fw-bold small mb-2">{{ $item->menu->name }}</p>
                                                    <div class="star-rating mb-2">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <input type="radio"
                                                                name="menu_ratings[{{ $item->menu_id }}][rating]"
                                                                id="mstar-{{ $order->id }}-{{ $item->menu_id }}-{{ $i }}"
                                                                value="{{ $i }}" required>
                                                            <label for="mstar-{{ $order->id }}-{{ $item->menu_id }}-{{ $i }}">★</label>
                                                        @endfor
                                                    </div>
                                                    <textarea name="menu_ratings[{{ $item->menu_id }}][review]"
                                                        class="form-control" rows="1"
                                                        placeholder="Gimana rasanya?"></textarea>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-outline-secondary rounded-3"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-orange rounded-3 px-4">Kirim Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3 shadow-sm p-5 text-center">
            <div style="font-size: 3rem;">📦</div>
            <h5 class="fw-bold mt-3">Belum ada pesanan</h5>
            <p class="text-muted mb-4">Yuk pesan makanan favoritmu!</p>
            <a href="{{ route('home') }}" class="btn btn-orange rounded-3 px-4">Cari Warung</a>
        </div>
    @endif
</div>
@endsection