<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            // Cek apakah mode demo aktif
            $isDemo = session('is_demo', false);
            $demoRole = session('demo_role', null);

            // Gunakan user demo atau user asli
            if ($isDemo) {
                $currentUser = (object) session('demo_user');
            } else {
                $currentUser = Auth::user();
            }

        @endphp

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
                    @if($currentUser && $currentUser->role === 'super_admin')
                        <x-nav-link :href="route('super_admin.dashboard')" :active="request()->routeIs('super_admin.dashboard')">
                            {{ __('Dasbor') }}
                        </x-nav-link>

                        <x-nav-link :href="route('super_admin.tenants.index')" :active="request()->routeIs('super_admin.tenants.*')">
                            {{ __('Tenant') }}
                        </x-nav-link>

                        <x-nav-link :href="route('super_admin.financial-report')" :active="request()->routeIs('super_admin.financial-report')">
                            {{ __('Laporan Keuangan') }}
                        </x-nav-link>

                        <x-nav-link :href="route('super_admin.reactivation.requests')" :active="request()->routeIs('super_admin.reactivation.*')">
                            <span class="flex items-center gap-2">
                                {{ __('Permintaan Reaktivasi') }}
                                @php
                                    $unreadCount = \App\Models\Notification::where('template', 'reactivation_request')
                                        ->whereNull('read_at')
                                        ->count();
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </span>
                        </x-nav-link>

                        <x-nav-link :href="route('super_admin.notifications.create')" :active="request()->routeIs('super_admin.notifications.create')">
                            {{ __('Kirim Notifikasi') }}
                        </x-nav-link>

                    @elseif($currentUser && $currentUser->role === 'admin')
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dasbor') }}
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

                        {{-- Hide "User Management" if staff in demo mode --}}
                        @if(!$isDemo || $demoRole !== 'staff')
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                {{ __('Manajemen Pengguna') }}
                            </x-nav-link>
                        @endif

                        {{-- DROPDOWN HISTORY (Admin: full access) --}}
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="right" width="56">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out h-full">
                                        <div>{{ __('Riwayat') }}</div>

                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('inventory-in.history')">{{ __('Riwayat Stok Masuk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('inventory-out.history')">{{ __('Riwayat Stok Keluar') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                    @elseif($currentUser && ($currentUser->role === 'staff' || $currentUser->role === 'staf'))
                        {{-- Staff: ordered navigation --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dasbor') }}
                        </x-nav-link>

                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                            {{ __('Produk') }}
                        </x-nav-link>

                        {{-- Dropdown for Stock In and Out --}}
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
                                    <x-dropdown-link :href="route('inventory-in.create')">{{ __('Catat Stok Masuk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('inventory-out.create')">{{ __('Catat Stok Keluar') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>

                        {{-- Dropdown for History In and Out --}}
                        <div class="hidden sm:flex sm:items-center">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out h-full">
                                        <div>{{ __('Riwayat') }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('inventory-in.history')">{{ __('Riwayat Stok Masuk') }}</x-dropdown-link>
                                    <x-dropdown-link :href="route('inventory-out.history')">{{ __('Riwayat Stok Keluar') }}</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        {{-- Fallback: show minimal links --}}
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dasbor') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- USER SETTINGS DROPDOWN (Right Top) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(!$isDemo && $currentUser)
                    @php
                        $unreadNotificationCount = \App\Models\Notification::where('recipient_id', $currentUser->id)
                            ->whereNull('read_at')
                            ->count();
                    @endphp
                    <a href="{{ route('notifications.index') }}" class="relative mr-4 text-gray-600 hover:text-gray-800" aria-label="Notifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if($unreadNotificationCount > 0)
                            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-600 rounded-full">
                                {{ $unreadNotificationCount }}
                            </span>
                        @endif
                    </a>
                @endif

                {{-- Demo Mode Badge --}}
                @if($isDemo)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300 mr-3">
                        🎭 DEMO MODE ({{ strtoupper($demoRole) }})
                    </span>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <span>{{ optional($currentUser)->name ?? 'Demo User' }}</span>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if($isDemo)
                            <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                <strong>Anda sedang dalam Mode Demo</strong><br>
                                Semua perubahan tidak akan disimpan
                            </div>
                            <x-dropdown-link :href="route('demo.exit')">
                                {{ __('🚪 Keluar Mode Demo') }}
                            </x-dropdown-link>
                        @else
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Keluar') }}
                                </x-dropdown-link>
                            </form>
                        @endif
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
            @if($currentUser && $currentUser->role === 'super_admin')
                <x-responsive-nav-link :href="route('super_admin.dashboard')" :active="request()->routeIs('super_admin.dashboard')">
                    {{ __('Dasbor') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('super_admin.tenants.index')" :active="request()->routeIs('super_admin.tenants.*')">
                    {{ __('Tenant') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('super_admin.financial-report')" :active="request()->routeIs('super_admin.financial-report')">
                    {{ __('Laporan Keuangan') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('super_admin.reactivation.requests')" :active="request()->routeIs('super_admin.reactivation.*')">
                    <span class="flex items-center gap-2">
                        {{ __('Permintaan Reaktivasi') }}
                        @php
                            $unreadCount = \App\Models\Notification::where('template', 'reactivation_request')
                                ->whereNull('read_at')
                                ->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </span>
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('super_admin.notifications.create')" :active="request()->routeIs('super_admin.notifications.create')">
                    {{ __('Kirim Notifikasi') }}
                </x-responsive-nav-link>

            @elseif($currentUser && $currentUser->role === 'admin')
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dasbor') }}
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

                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Manajemen Pengguna') }}
                </x-responsive-nav-link>

                <div class="border-t border-gray-200 mt-2 pt-2 pb-2">
                    <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                        {{ __('Riwayat') }}
                    </div>

                    <x-responsive-nav-link :href="route('inventory-in.history')" :active="request()->routeIs('inventory-in.history')">
                        {{ __('Riwayat Stok Masuk') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('inventory-out.history')" :active="request()->routeIs('inventory-out.history')">
                        {{ __('Riwayat Stok Keluar') }}
                    </x-responsive-nav-link>
                </div>

            @elseif($currentUser && ($currentUser->role === 'staff' || $currentUser->role === 'staf'))
                {{-- Staff: ordered navigation for mobile --}}
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dasbor') }}
                </x-responsive-nav-link>

                <div class="border-t border-gray-200 mt-2 pt-2">
                    <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                        {{ __('Data Master') }}
                    </div>
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.index')">
                        {{ __('Produk') }}
                    </x-responsive-nav-link>
                </div>

                <div class="border-t border-gray-200 mt-2 pt-2 pb-2">
                    <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                        {{ __('Stok') }}
                    </div>
                    <x-responsive-nav-link :href="route('inventory-in.create')" :active="request()->routeIs('inventory-in.create')">
                        {{ __('Catat Stok Masuk') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('inventory-out.create')" :active="request()->routeIs('inventory-out.create')">
                        {{ __('Catat Stok Keluar') }}
                    </x-responsive-nav-link>
                </div>

                <div class="border-t border-gray-200 mt-2 pt-2 pb-2">
                    <div class="px-4 py-2 text-xs text-gray-400 font-semibold uppercase">
                        {{ __('Riwayat Persediaan') }}
                    </div>
                    <x-responsive-nav-link :href="route('inventory-in.history')" :active="request()->routeIs('inventory-in.history')">
                        {{ __('Riwayat Stok Masuk') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('inventory-out.history')" :active="request()->routeIs('inventory-out.history')">
                        {{ __('Riwayat Stok Keluar') }}
                    </x-responsive-nav-link>
                </div>
            @else
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dasbor') }}
                </x-responsive-nav-link>
            @endif
        </div>

        {{-- SETTINGS USER (MOBILE) --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 flex items-center gap-2">
                    <span>{{ optional($currentUser)->name ?? 'Demo User' }}</span>
                    @if(isset($isDemoMode) && $isDemoMode)
                        <span class="px-2 py-0.5 text-xs rounded bg-orange-100 text-orange-700 border border-orange-300">Demo</span>
                    @endif
                </div>
                <div class="font-medium text-sm text-gray-500">{{ optional($currentUser)->email ?? 'demo@example.com' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if($isDemo)
                    <div class="px-4 py-2 text-xs text-gray-500 border-b">
                        <strong>Mode Demo Aktif</strong><br>
                        Semua perubahan tidak disimpan
                    </div>
                    <x-responsive-nav-link :href="route('demo.exit')">
                        {{ __('🚪 Keluar Mode Demo') }}
                    </x-responsive-nav-link>
                @else
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Keluar') }}
                        </x-responsive-nav-link>
                    </form>
                @endif
            </div>
        </div>
    </div>
</nav>
