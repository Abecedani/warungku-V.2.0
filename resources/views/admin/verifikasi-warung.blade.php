@extends('layouts.admin')

@section('title', 'Verifikasi Warung - Admin WarungKu')

@section('content')
<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Verifikasi Warung</h4>
            <p class="text-muted small mb-0">Kelola warung yang mendaftar di platform</p>
        </div>
        <span class="badge bg-danger rounded-pill px-3 py-2">
            {{ $warungs->where('is_verified', false)->count() }} Menunggu
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3 shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Warung</th>
                    <th class="py-3">Pemilik</th>
                    <th class="py-3">Lokasi</th>
                    <th class="py-3">Daftar</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($warungs as $warung)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="fw-bold mb-0">{{ $warung->name }}</p>
                            <small class="text-muted">{{ Str::limit($warung->description, 40) }}</small>
                        </td>
                        <td class="py-3">
                            <p class="mb-0 small">{{ $warung->user->name }}</p>
                            <small class="text-muted">{{ $warung->user->email }}</small>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $warung->location_detail ?? '-' }}</small>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $warung->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="py-3">
                            @if($warung->is_verified)
                                <span class="badge bg-success rounded-pill px-3">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                @if(!$warung->is_verified)
                                    <form method="POST" action="{{ route('admin.warungs.verify', $warung->id) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-success rounded-3">
                                            <i class="bi bi-check-lg me-1"></i>Verifikasi
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.warungs.reject', $warung->id) }}"
                                        onsubmit="return confirm('Tolak dan hapus warung ini?')">
                                        @csrf
                                        <button class="btn btn-sm btn-danger rounded-3">
                                            <i class="bi bi-x-lg me-1"></i>Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div style="font-size: 2rem;">🏪</div>
                            <p class="mt-2 mb-0">Belum ada warung terdaftar</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>
@endsection