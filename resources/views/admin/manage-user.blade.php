@extends('layouts.admin')

@section('title', 'Manajemen User - Admin WarungKu')

@section('content')
<main class="flex-grow-1 p-4" style="background: #fafafa; min-height: 100vh;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Manajemen User</h4>
            <p class="text-muted small mb-0">Kelola seluruh pengguna platform</p>
        </div>
        <span class="badge bg-secondary rounded-pill px-3 py-2">
            {{ $users->count() }} Pengguna
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-3 shadow-sm">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">No. HP</th>
                    <th class="py-3">Role</th>
                    <th class="py-3">Warung</th>
                    <th class="py-3">Daftar</th>
                    <th class="py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="sidebar-avatar" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <p class="fw-bold mb-0 small">{{ $user->name }}</p>
                            </div>
                        </td>
                        <td class="py-3">
                            <small>{{ $user->email }}</small>
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $user->phone_number ?? '-' }}</small>
                        </td>
                        <td class="py-3">
                            @if($user->role === 'penjual')
                                <span class="badge bg-warning text-dark rounded-pill px-3">Penjual</span>
                            @else
                                <span class="badge bg-info text-dark rounded-pill px-3">Pembeli</span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($user->warung)
                                <small class="fw-bold">{{ $user->warung->name }}</small>
                                <br>
                                @if($user->warung->is_verified)
                                    <span class="badge bg-success rounded-pill" style="font-size: 0.65rem;">Terverifikasi</span>
                                @else
                                    <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;">Belum Verifikasi</span>
                                @endif
                            @else
                                <small class="text-muted">—</small>
                            @endif
                        </td>
                        <td class="py-3">
                            <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                        </td>
                        <td class="py-3">
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                onsubmit="return confirm('Hapus user {{ $user->name }}? Semua data terkait akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <div style="font-size: 2rem;">👤</div>
                            <p class="mt-2 mb-0">Belum ada pengguna terdaftar</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>
@endsection