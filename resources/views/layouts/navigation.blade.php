<nav x-data="{ open: false }"
    class="bg-white border-b border-gray-100 px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">

    {{-- Logo --}}
    <div class="flex items-center">
        <a href="/" class="flex items-center gap-2 font-bold text-lg">
            <img src="{{ asset('assets/WarungKu-Logo.png') }}" alt="Logo" class="h-9 w-auto">
            <span class="font-bold text-lg hidden sm:block">
                <span class="text-orange-600">Warung</span><span class="text-black">Ku</span>
            </span>
        </a>
    </div>

    <div class="flex items-center gap-3">

        {{-- GUEST --}}
        @guest
            <div class="hidden sm:flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-orange-600">Masuk</a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700">Daftar</a>
            </div>
        @endguest

        @auth
            {{-- PENJUAL & ADMIN --}}
            @if(in_array(auth()->user()->role, ['penjual', 'admin']))
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                                <div class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 text-xs text-gray-500 border-b">{{ auth()->user()->name }}</div>
                            <x-dropdown-link :href="route('dashboard')">Dashboard</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif

            {{-- PEMBELI --}}
            @if(auth()->user()->role === 'pembeli')
                <div class="hidden sm:flex items-center gap-3">

                    {{-- Icon Keranjang --}}
                    @php $cartCount = auth()->user()->carts()->sum('quantity'); @endphp
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-orange-600 transition">
                        <i class="bi bi-cart3" style="font-size: 1.4rem;"></i>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-orange-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Avatar Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-1 hover:opacity-80 transition">
                                <div class="w-9 h-9 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 border-b">
                                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('dashboard')">Dashboard</x-dropdown-link>
                            <x-dropdown-link :href="route('pembeli.orders')">Pesanan Saya</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif
        @endauth

        {{-- Mobile hamburger --}}
        <div class="flex items-center sm:hidden ml-2">
            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>
        </div>
    </div>
</nav>

{{-- Mobile Menu --}}
<div :class="{ 'block': open, 'hidden': !open }"
    class="hidden sm:hidden w-full absolute top-16 left-0 bg-white border-b shadow-lg z-50">
    <div class="pt-2 pb-3 space-y-1 px-4">
        @guest
            <a href="{{ route('login') }}"
                class="block py-2 text-base font-medium text-gray-700 hover:text-orange-600">Masuk</a>
            <a href="{{ route('register') }}"
                class="block py-2 text-base font-medium text-orange-600">Daftar</a>
        @endguest

        @auth
            <div class="py-2 border-b mb-2">
                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="block py-2 text-base text-gray-700 hover:text-orange-600">Dashboard</a>
            @if(auth()->user()->role === 'pembeli')
                <a href="{{ route('cart.index') }}" class="block py-2 text-base text-gray-700 hover:text-orange-600">Keranjang</a>
                <a href="{{ route('pembeli.orders') }}" class="block py-2 text-base text-gray-700 hover:text-orange-600">Pesanan Saya</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="block py-2 text-base text-gray-700 hover:text-orange-600">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left py-2 text-base text-red-600 hover:text-red-700">Keluar</button>
            </form>
        @endauth
    </div>
</div>