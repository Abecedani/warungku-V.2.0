@extends('layouts.main')

@section('title', 'Profil Warung - WarungKu')

@section('content')
<link rel="stylesheet" href="{{ asset('css/penjual-dashboard.css') }}">

<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Profil Warung</h4>
            <p class="text-muted small mb-0">Kelola informasi warungmu</p>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Form Edit Profil --}}
        <div class="col-md-8">
            <div class="bg-white rounded-3 shadow-sm p-4">
                <h6 class="fw-bold mb-4">📋 Informasi Warung</h6>
                <form method="POST" action="{{ route('warungs.profil.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Nama Warung</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $warung->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Lokasi Detail</label>
                        <input type="text" name="location_detail" class="form-control"
                            value="{{ old('location_detail', $warung->location_detail) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="small fw-bold mb-1">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"
                            placeholder="Ceritakan keunggulan warungmu...">{{ old('description', $warung->description) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-orange rounded-3 px-4">
                        <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Sidebar --}}
        <div class="col-md-4">

            <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
                <h6 class="fw-bold mb-3">📊 Status Warung</h6>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Status</span>
                    <span class="badge {{ $warung->is_open ? 'bg-success' : 'bg-danger' }}">
                        {{ $warung->is_open ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Verifikasi</span>
                    <span class="badge {{ $warung->is_verified ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $warung->is_verified ? 'Terverifikasi' : 'Menunggu' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small">Rating</span>
                    <span class="fw-bold" style="color: var(--orange);">
                        ⭐ {{ $warung->rating ?? '0.0' }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Total Menu</span>
                    <span class="fw-bold">{{ $warung->menus->count() }}</span>
                </div>
            </div>

            <div class="bg-white rounded-3 shadow-sm p-4 border border-danger border-opacity-25">
                <h6 class="fw-bold mb-1 text-danger">⚠️ Hapus warung</h6>
                <p class="text-muted small mb-3">Tindakan ini tidak bisa dibatalkan.</p>
                <button class="btn btn-outline-danger btn-sm rounded-3 w-100"
                    data-bs-toggle="modal" data-bs-target="#modalHapus">
                    <i class="bi bi-trash me-2"></i>Hapus
                </button>
            </div>

        </div>
    </div>

</main>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">⚠️ Hapus Warung</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Apakah kamu yakin ingin menghapus warung <strong>{{ $warung->name }}</strong>? Semua data menu dan pesanan akan ikut terhapus.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('warungs.profil.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-3 px-4">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection