@extends('layouts.main')

@section('title', 'Daftar Warung - WarungKu')

@section('content')
<style>
    .warung-card { transition: 0.2s; }
    .warung-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important; }
    .badge-buka  { background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .badge-tutup { background: #f8d7da; color: #721c24; padding: 4px 12px; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
    .btn-orange  { background: #e65c00; color: white; }
    .btn-orange:hover { background: #cc5200; color: white; }
</style>

<div class="container py-5">
    <div class="mb-5">
        <h3 class="fw-bold mb-1">🏪 Daftar Warung</h3>
        <p class="text-muted">Temukan warung favoritmu di Universitas Mataram</p>
    </div>

    @if($warungs->count() > 0)
        <div class="row g-4">
            @foreach($warungs as $warung)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 h-100 warung-card">
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-top"
                            style="height: 160px; font-size: 3rem;">🏪</div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0">{{ $warung->name }}</h5>
                                <span class="{{ $warung->is_open ? 'badge-buka' : 'badge-tutup' }}">
                                    {{ $warung->is_open ? '🟢 Buka' : '🔴 Tutup' }}
                                </span>
                            </div>
                            <p class="text-muted small mb-1">
                                <i class="bi bi-geo-alt me-1"></i>{{ $warung->location_detail }}
                            </p>
                            <p class="text-muted small mb-3">{{ Str::limit($warung->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-list-ul me-1"></i>{{ $warung->menus->count() }} menu
                                </small>
                                <a href="{{ route('warung.show', $warung) }}"
                                    class="btn btn-sm btn-orange rounded-3 px-3">
                                    Lihat Menu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div style="font-size: 3rem;">🏪</div>
            <h5 class="fw-bold mt-3">Belum ada warung</h5>
            <p class="text-muted">Warung akan muncul di sini setelah diverifikasi admin.</p>
        </div>
    @endif
</div>
@endsection