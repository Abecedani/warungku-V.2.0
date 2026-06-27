@extends('layouts.main')

@section('title', $warung->name . ' - WarungKu')

@section('content')
    <style>
        .btn-orange {
            background: #e65c00;
            color: white;
        }

        .btn-orange:hover {
            background: #cc5200;
            color: white;
        }

        .text-orange {
            color: #e65c00;
        }

        .menu-card {
            transition: 0.2s;
        }

        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08) !important;
        }

        .badge-buka {
            background: #d4edda;
            color: #155724;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-tutup {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 600;
        }
    </style>

    <div class="container py-5">

        {{-- Breadcrumb --}}
        <nav class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none text-orange">Beranda</a>
                </li>
                <li class="breadcrumb-item active">{{ $warung->name }}</li>
            </ol>
        </nav>

        {{-- Warung Header --}}
        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="fw-bold mb-1">{{ $warung->name }}</h3>
                    <p class="text-muted mb-1">
                        <i class="bi bi-geo-alt me-1"></i>{{ $warung->location_detail }}
                    </p>
                    <p class="text-muted small mb-0">{{ $warung->description }}</p>
                </div>
                <span class="{{ $warung->is_open ? 'badge-buka' : 'badge-tutup' }}">
                    {{ $warung->is_open ? '🟢 Buka' : '🔴 Tutup' }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">{{ session('error') }}</div>
        @endif

        {{-- Menu List --}}
        @if($categories->count() > 0)
            @foreach($categories as $category)
                <h6 class="fw-bold text-uppercase text-muted mb-3">{{ $category->name }}</h6>
                <div class="row g-3 mb-4">
                    @foreach($menus->where('menu_category_id', $category->id) as $menu)
                        @include('pembeli._menu-card', ['menu' => $menu])
                    @endforeach
                </div>
            @endforeach

            @php $uncategorized = $menus->whereNull('menu_category_id'); @endphp
            @if($uncategorized->count() > 0)
                <h6 class="fw-bold text-uppercase text-muted mb-3">Lainnya</h6>
                <div class="row g-3 mb-4">
                    @foreach($uncategorized as $menu)
                        @include('pembeli._menu-card', ['menu' => $menu])
                    @endforeach
                </div>
            @endif
        @else
            <div class="row g-3">
                @forelse($menus as $menu)
                    @include('pembeli._menu-card', ['menu' => $menu])
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <p>Belum ada menu tersedia.</p>
                    </div>
                @endforelse
            </div>
        @endif

        <script>
            function toggleVarian(id, btn) {
                const el = document.getElementById('varian-' + id);
                const isHidden = el.style.display === 'none';
                el.style.display = isHidden ? 'block' : 'none';
                btn.innerHTML = isHidden
                    ? 'Pilih Varian <i class="bi bi-chevron-up ms-1"></i>'
                    : 'Pilih Varian <i class="bi bi-chevron-down ms-1"></i>';
            }
        </script>
    </div>
@endsection