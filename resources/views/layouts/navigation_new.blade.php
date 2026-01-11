<nav x-data="{ open: false, userDropdown: false }" class="bg-white/95 backdrop-blur-md shadow-lg m-0 fixed w-full top-0 z-50 py-2 px-4 sm:px-6 lg:px-8">
    <div class="w-full">
        @php
            $isDemo = session('is_demo', false);
            $demoRole = session('demo_role', null);
            if ($isDemo) {
                $currentUser = (object) session('demo_user');
            } else {
                $currentUser = Auth::user();
            }
        @endphp

        <!-- Main Navbar Container - Rounded and Compact -->
        <div class="flex justify-between items-center rounded-full bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-3 h-14">

            <!-- Left: Logo + Brand + Menu -->
            <div class="flex items-center gap-6">
                <!-- Logo & Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <x-application-logo class="block h-8 w-auto" />
                        <span class="hidden sm:inline font-bold text-gray-900 text-sm">StockMaster</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-0.5">
                    @if(Auth::check() && Auth::user()->role === 'super_admin')
                        <a href="{{ route('super_admin.dashboard') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.dashboard') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Beranda
                        </a>
                        <a href="{{ route('super_admin.tenants.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.tenants.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Tenant
                        </a>
                        <a href="{{ route('super_admin.reactivation.requests') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.reactivation.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Permintaan Reaktivasi
                        </a>
                        <a href="{{ route('super_admin.financial-report') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.financial-report*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Laporan
                        </a>
                        <a href="{{ route('super_admin.notifications.create') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('super_admin.notifications.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Kirim Notifikasi
                        </a>
                    @elseif($currentUser && ($currentUser->role === 'admin' || $currentUser->role === 'super_admin'))
                        <a href="{{ route('dashboard') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('dashboard') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Beranda
                        </a>
                        <a href="{{ route('products.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('products.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Produk
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('suppliers.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Pemasok
                        </a>
                        <a href="{{ route('categories.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('categories.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                            Kategori
                        </a>
                        @if(!$isDemo || $demoRole !== 'staff')
                            <a href="{{ route('users.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('users.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                                Pengguna
                            </a>
                        @endif
                        @if(!$isDemo || ($isDemo && $demoRole === 'admin'))
                            <a href="{{ route('audit-logs.index') }}" class="px-3 py-1 text-sm font-medium rounded-full {{ request()->routeIs('audit-logs.*') ? 'bg-[#1F8F6A] text-white' : 'text-gray-700 hover:bg-gray-200' }} transition">
                                Aktivitas
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Right: Notifications and User -->
            <div class="flex items-center gap-3">
                <!-- Notification Icon -->
                @if($currentUser && in_array($currentUser->role ?? $demoRole, ['admin', 'super_admin']))
                    <a href="{{ route('notifications.index') }}" aria-label="Notifikasi" class="relative z-10 block p-2 rounded-full text-gray-600 hover:text-[#1F8F6A] hover:bg-gray-200 transition cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1F8F6A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </a>
                @endif

                <!-- User Dropdown -->
                <div class="relative" @click="userDropdown = !userDropdown">
                    <button class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white hover:bg-gray-50 border border-gray-200 text-sm text-gray-700 transition hover:shadow-md">
                        <span class="font-semibold hidden sm:inline">{{ $currentUser->email ?? 'User' }}</span>
                        <svg class="w-4 h-4 transition" :class="{'rotate-180': userDropdown}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userDropdown" @click.away="userDropdown = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-20">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-600">Signed in as</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $currentUser->email ?? 'User' }}</p>
                        </div>

                        @if($isDemo)
                            <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100 bg-amber-50">
                                🎭 <strong>Demo Mode</strong> - Perubahan tidak disimpan
                            </div>
                        @endif

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                            <span class="font-medium">Profil</span>
                        </a>

                        <div class="border-t border-gray-100"></div>

                        @if($isDemo)
                            <a href="{{ route('demo.exit') }}" class="block px-4 py-2 text-sm text-orange-600 hover:bg-orange-50 transition font-medium">
                                🚪 Keluar Demo
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                                    Logout
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="lg:hidden p-1.5 rounded-full text-gray-600 hover:text-[#1F8F6A] hover:bg-gray-200 transition">
                    <svg class="w-5 h-5" :class="{'hidden': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="w-5 h-5 hidden" :class="{'hidden': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="lg:hidden mt-2 bg-white rounded-lg shadow-lg p-3">
            @if($currentUser)
                @if($currentUser->role === 'super_admin')
                    <a href="{{ route('super_admin.dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Beranda</a>
                    <a href="{{ route('super_admin.tenants.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Tenant</a>
                    <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Aktivitas</a>
                    <a href="{{ route('super_admin.reactivation.requests') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Permintaan Reaktivasi</a>
                    <a href="{{ route('super_admin.financial-report') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Laporan</a>
                    <a href="{{ route('super_admin.notifications.create') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Kirim Notifikasi</a>
                @elseif($currentUser->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Beranda</a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Produk</a>
                    <a href="{{ route('suppliers.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Pemasok</a>
                    <a href="{{ route('categories.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Kategori</a>
                    <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Pengguna</a>
                    <a href="{{ route('audit-logs.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded transition">Aktivitas</a>
                @endif
            @endif
        </div>
    </div>
</nav>
