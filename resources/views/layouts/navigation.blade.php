<nav x-data="{ open: false }" class="fixed w-full top-0 z-50 px-4 sm:px-6 lg:px-8 py-3">
    <div class="w-full">
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

        <!-- Main Navbar Container - Rounded and Compact -->
        <div class="flex justify-between items-center rounded-full bg-white px-6 py-3 h-14 shadow-sm border border-slate-200">
            <!-- Left: Logo + Brand -->
            <div class="flex items-center gap-6">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto" />
                    </a>
                </div>

                <!-- Menu Items Desktop -->
                <div class="hidden lg:flex items-center gap-1">
                    @if($currentUser && $currentUser->role === 'super_admin')
                        <a href="{{ route('super_admin.dashboard') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.dashboard') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Beranda') }}</a>
                        <a href="{{ route('super_admin.tenants.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.tenants.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Tenant') }}</a>
                        <a href="{{ route('audit-logs.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('audit-logs.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Aktivitas') }}</a>
                        <a href="{{ route('super_admin.reactivation.requests') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.reactivation.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Permintaan Reaktivasi') }}</a>
                        <a href="{{ route('super_admin.financial-report') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.financial-report*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Laporan') }}</a>
                        <a href="{{ route('super_admin.notifications.create') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.notifications.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Kirim Notifikasi') }}</a>
                    @elseif($currentUser && $currentUser->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('dashboard') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Beranda') }}</a>
                        <a href="{{ route('products.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('products.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Produk') }}</a>
                        <a href="{{ route('suppliers.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('suppliers.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Pemasok') }}</a>
                        <a href="{{ route('categories.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('categories.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Kategori') }}</a>

                        @if(!$isDemo || $demoRole !== 'staff')
                            <a href="{{ route('users.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('users.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Pengguna') }}</a>
                        @endif
                        @if(!$isDemo || ($isDemo && $demoRole === 'admin'))
                            <a href="{{ route('audit-logs.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('audit-logs.*') ? 'bg-[#1F8F6A] text-white' : 'text-slate-700 hover:bg-gray-200' }} transition">{{ __('Aktivitas') }}</a>
                        @endif
                        <!-- Dropdown Riwayat -->
                        <div class="relative" x-data="{ openRiwayat: false }">
                            <button @click="openRiwayat = !openRiwayat" class="px-3 py-1 text-sm font-medium {{ request()->routeIs('inventory-in.*') || request()->routeIs('inventory-out.*') ? 'text-[#1F8F6A] border-b-2 border-[#1F8F6A]' : 'text-slate-700 hover:text-[#1F8F6A]' }} transition flex items-center gap-1">
                                {{ __('Riwayat') }}
                                <svg class="w-4 h-4" :class="{'rotate-180': openRiwayat}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="openRiwayat" @click.away="openRiwayat = false" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                <a href="{{ route('inventory-in.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">{{ __('Stok Masuk') }}</a>
                                <a href="{{ route('inventory-out.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">{{ __('Stok Keluar') }}</a>
                            </div>
                        </div>                    @else
                        <a href="{{ route('dashboard') }}" class="px-3 py-1 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-[#1F8F6A]' : 'text-slate-700 hover:text-[#1F8F6A]' }} transition">{{ __('Beranda') }}</a>
                    @endif
                </div>
            </div>

            <!-- Right: Notifications and User Menu -->
            <div class="flex items-center gap-4">
                <!-- Notification Icon -->
                <button class="p-1 rounded-full text-slate-600 hover:text-[#1F8F6A] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>

                <!-- User Menu Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-white hover:bg-[#E9F6F1] border border-[#D1EDE5] text-sm text-slate-800 transition">
                        <span class="font-medium">{{ $currentUser->email ?? 'User' }}</span>
                        <svg class="w-4 h-4" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">{{ __('Profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">{{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden p-1 rounded-lg text-slate-600 hover:text-[#1F8F6A] transition">
                    <svg class="w-5 h-5" :class="{'hidden': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="w-5 h-5" :class="{'hidden': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="lg:hidden mt-3 bg-white rounded-lg shadow-lg p-4">
            @if($currentUser)
                @if($currentUser->role === 'super_admin')
                    <a href="{{ route('super_admin.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Beranda') }}</a>
                    <a href="{{ route('super_admin.tenants.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Tenant') }}</a>
                    <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Aktivitas') }}</a>
                    <a href="{{ route('super_admin.reactivation.requests') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Permintaan Reaktivasi') }}</a>
                    <a href="{{ route('super_admin.financial-report') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Laporan') }}</a>
                    <a href="{{ route('super_admin.notifications.create') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Kirim Notifikasi') }}</a>
                @elseif($currentUser->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Beranda') }}</a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Produk') }}</a>
                    <a href="{{ route('suppliers.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Pemasok') }}</a>
                    <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Kategori') }}</a>
                    <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Pengguna') }}</a>
                    <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Aktivitas') }}</a>

                    <!-- Dropdown Riwayat Mobile -->
                    <div class="border-t border-gray-200 mt-2 pt-2">
                        <div class="px-3 py-1 text-xs text-gray-500 font-semibold uppercase">{{ __('Riwayat') }}</div>
                        <a href="{{ route('inventory-in.history') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Stok Masuk') }}</a>
                        <a href="{{ route('inventory-out.history') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">{{ __('Stok Keluar') }}</a>
                    </div>
                @endif
            @endif
        </div>
    </div>
</nav>






