<aside class="sidebar bg-white p-3">
    <div class="d-flex align-items-center gap-2 p-3 rounded-3 mb-4" style="background: var(--orange-light);">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden">
            <p class="fw-bold mb-0 small text-truncate">{{ auth()->user()->warung->name ?? 'Warung Saya' }}</p>
            <p class="text-muted mb-0" style="font-size: 0.75rem;">{{ auth()->user()->name }}</p>
        </div>
    </div>

    <p class="text-muted small fw-bold text-uppercase mb-2 px-2">Menu</p>
    <a href="{{ route('warungs.dashboard') }}" class="{{ request()->routeIs('warungs.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('warungs.menu') }}" class="{{ request()->routeIs('warungs.menu') ? 'active' : '' }}">
        <i class="bi bi-list-ul"></i> Kelola Menu
    </a>
    <a href="{{ route('warungs.pesanan') }}" class="{{ request()->routeIs('warungs.pesanan') ? 'active' : '' }}">
        <i class="bi bi-bag-check"></i> Pesanan
    </a>
    <a href="{{ route('warungs.profil') }}" class="{{ request()->routeIs('warungs.profil') ? 'active' : '' }}">
        <i class="bi bi-shop"></i> Profil Warung
    </a>
</aside>
