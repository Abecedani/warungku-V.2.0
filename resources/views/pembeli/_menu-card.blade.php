<div class="col-md-6">
    <div class="bg-white rounded-3 shadow-sm menu-card h-100">
        <div class="d-flex h-100">
            <div class="flex-shrink-0">
                @php $menuImg = $menu->images->first()?->path; @endphp
                @if($menuImg)
                    <img src="{{ asset('storage/' . $menuImg) }}" class="rounded-start"
                        style="width: 100px; height: 100%; object-fit: cover;" alt="{{ $menu->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light rounded-start"
                        style="width: 100px; height: 100%; font-size: 2rem;">🍽️</div>
                @endif
            </div>
            <div class="p-3 flex-grow-1 d-flex flex-column justify-content-between">
                <div>
                    <h6 class="fw-bold mb-1">{{ $menu->name }}</h6>
                    <p class="text-muted small mb-1">{{ Str::limit($menu->description, 50) }}</p>
                    <p class="fw-bold mb-0 text-orange">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                </div>

                @auth
                    @if($menu->status === 'tersedia')
                        @if($menu->variants->count() > 0)
                            {{-- Ada varian --}}
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-3 w-100"
                                    onclick="toggleVarian({{ $menu->id }}, this)">
                                    Pilih Varian <i class="bi bi-chevron-down ms-1"></i>
                                </button>

                                <div id="varian-{{ $menu->id }}" style="display: none;" class="mt-2">
                                    @foreach($menu->variants as $variant)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div>
                                                <p class="small fw-bold mb-0">{{ $variant->name }}</p>
                                                <small class="text-orange">Rp {{ number_format($variant->price, 0, ',', '.') }}</small>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <form method="POST" action="{{ route('cart.add', $menu) }}">
                                                    @csrf
                                                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-3">
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('checkout.buy-now') }}">
                                                    @csrf
                                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                    <input type="hidden" name="variant_id" value="{{ $variant->id }}">
                                                    <button class="btn btn-sm btn-orange rounded-3">Beli</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            {{-- Tidak ada varian --}}
                            <div class="d-flex gap-2 mt-2">
                                <form method="POST" action="{{ route('cart.add', $menu) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary rounded-3">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('checkout.buy-now') }}">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <button class="btn btn-sm btn-orange rounded-3 px-3">Beli</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <span class="badge bg-secondary mt-2">Habis</span>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-orange rounded-3 mt-2">Login untuk beli</a>
                @endauth
            </div>
        </div>
    </div>
</div>