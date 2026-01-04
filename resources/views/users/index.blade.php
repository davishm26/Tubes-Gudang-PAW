<x-app-layout>
    <x-slot name="title">Manajemen Pengguna - StockMaster</x-slot>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">{{ __('Manajemen User') }}</h2>
            @if($isDemo)
                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                    Demo Mode - Read Only
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($isDemo)
                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-800">
                        <strong>🎭 Demo Mode:</strong> Tampilan sama seperti mode real, tetapi semua perubahan tidak akan disimpan.
                    </p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-4 p-3 rounded bg-sky-50 text-sky-800 border border-sky-200">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Pengguna</h3>
                    @if(!$isDemo)
                    <a href="{{ route('users.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                        + Tambah Pengguna
                    </a>
                    @endif
                </div>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user (nama, email, role)..." class="flex-1 px-4 py-2 border border-emerald-200 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-r-xl font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-emerald-200">
                        <thead class="bg-emerald-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-emerald-700 uppercase">Nama</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-emerald-700 uppercase">Email</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-emerald-700 uppercase">Role</th>
                                <th class="py-3 px-4 text-right text-xs font-semibold text-emerald-700 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-emerald-50/30 transition">
                                    <td class="py-3 px-4 text-slate-900 font-medium">{{ $user->name ?? '-' }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $user->email ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $user->role === 'staf' ? 'Staf' : ($user->role ?? '-') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        @if(!$isDemo)
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-emerald-600 hover:text-emerald-900 font-medium mr-3">Edit</a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 font-medium">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-slate-400 text-sm">Read Only</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4" colspan="4">Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
