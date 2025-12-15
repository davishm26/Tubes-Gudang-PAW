<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- LOGO --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- MENU DESKTOP (Tampilan Laptop/PC) --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Produk') }}
                    </x-nav-link>

                    <x-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                        {{ __('Pemasok') }}
                    </x-nav-link>

                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        {{ __('Kategori') }}
                    </x-nav-link>


                    {{-- DROPDOWN STOK (PENCATATAN BARU) --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out h-full">
                                    <div>{{ __('Stok') }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Link ke Form Catat Stok Masuk --}}
                                <x-dropdown-link :href="route('inventory-in.create')">
                                    {{ __('Catat Stok Masuk') }}
                                </x-dropdown-link>

                                {{-- Link ke Form Catat Stok Keluar --}}
                                <x-dropdown-link :href="route('inventory-out.create')">
                                    {{ __('Catat Stok Keluar') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    
                    {{-- DROPDOWN HISTORY (RIWAYAT) --}}
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out h-full">
                                    <div>{{ __('History') }}</div>

                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Link ke Riwayat Masuk --}}
                                <x-dropdown-link :href="route('inventory-in.history')">
                                    {{ __('Riwayat Masuk') }}
                                </x-dropdown-link>

                                {{-- Link ke Riwayat Keluar --}}
                                <x-dropdown-link :href="route('inventory-out.history')">
                                    {{ __('Riwayat Keluar') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                </div>
            </div>

            {{-- USER SETTINGS DROPDOWN (Kanan Atas) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- TOMBOL HAMBURGER (Untuk Mobile) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU MOBILE (Tampilan HP) --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Produk') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                {{ __('Pemasok') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                {{ __('Kategori') }}
            </x-responsive-nav-link>

            {{-- MENU HISTORY (MOBILE) --}}
            <div class="border-t border-gray-200 mt-2 pt-2 pb-2">
                <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                    {{ __('Laporan History') }}
                </div>

                <x-responsive-nav-link :href="route('inventory-in.history')" :active="request()->routeIs('inventory-in.history')">
                    {{ __('Riwayat Masuk') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('inventory-out.history')" :active="request()->routeIs('inventory-out.history')">
                    {{ __('Riwayat Keluar') }}
                </x-responsive-nav-link>
            </div>

            {{-- MENU STOK (MOBILE) --}}
            <div class="border-t border-gray-200 mt-2 pt-2 pb-2">
                <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                    {{ __('Pencatatan Stok') }}
                </div>

                <x-responsive-nav-link :href="route('inventory-in.create')" :active="request()->routeIs('inventory-in.create')">
                    {{ __('Catat Stok Masuk') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('inventory-out.create')" :active="request()->routeIs('inventory-out.create')">
                    {{ __('Catat Stok Keluar') }}
                </x-responsive-nav-link>
            </div>
        </div>

        {{-- SETTINGS USER (MOBILE) --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
