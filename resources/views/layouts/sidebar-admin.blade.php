<aside class="sidebar bg-white p-3">
    <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-4" style="background: var(--orange-light);">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden">
            <p class="fw-bold mb-0 small text-truncate">Admin Panel</p>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ auth()->user()->name }}</p>
        </div>
    </div>

    <p class="text-muted small fw-bold text-uppercase mb-2 px-2">Menu</p>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('admin.warungs') }}" class="{{ request()->routeIs('admin.warungs') ? 'active' : '' }}">
        <i class="bi bi-shop"></i> Verifikasi Warung
        @php $pending = \App\Models\Warung::where('is_verified', false)->count(); @endphp
        @if($pending > 0)
            <span class="badge bg-danger ms-1">{{ $pending }}</span>
        @endif
    </a>
    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
        <i class="bi bi-people"></i> Manajemen User
    </a>
    <a href="{{ route('admin.transaksi') }}" class="{{ request()->routeIs('admin.transaksi') ? 'active' : '' }}">
        <i class="bi bi-receipt"></i> Transaksi
    </a>
    <a href="{{ route('admin.pengaturan') }}" class="{{ request()->routeIs('admin.pengaturan') ? 'active' : '' }}">
        <i class="bi bi-gear"></i> Pengaturan
    </a>
</aside>