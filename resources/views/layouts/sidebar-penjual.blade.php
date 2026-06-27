<div class="h-screen w-64 bg-white shadow-lg fixed left-0 top-0 pt-20">
    <div class="p-4">
        <h2 class="text-orange-600 font-bold text-xl mb-6">WarungKu</h2>
        <nav class="space-y-2">
            <a href="{{ route('warungs.dashboard') }}" class="block p-3 rounded-lg hover:bg-orange-100 {{ request()->routeIs('warungs.dashboard') ? 'bg-orange-500 text-white' : 'text-gray-700' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('warungs.menus.index') }}" class="block p-3 rounded-lg hover:bg-orange-100 {{ request()->routeIs('warungs.menus.index') ? 'bg-orange-500 text-white' : 'text-gray-700' }}">
                <i class="bi bi-list-ul me-2"></i> Kelola Menu
            </a>
            <a href="#" class="block p-3 rounded-lg hover:bg-orange-100 text-gray-700">
                <i class="bi bi-bag-check me-2"></i> Pesanan
            </a>
            <a href="#" class="block p-3 rounded-lg hover:bg-orange-100 text-gray-700">
                <i class="bi bi-shop me-2"></i> Profil Warung
            </a>
        </nav>
    </div>
</div>
