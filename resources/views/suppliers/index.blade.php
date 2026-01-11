<x-app-layout>
    <x-slot name="title">Manajemen Pemasok - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">{{ __('Manajemen Pemasok') }}</h2>
            </div>
        </div>
    </x-slot>

    @php
        // Cek mode demo
        $isDemo = session('is_demo', false);
        $demoRole = session('demo_role', null);

        // Tentukan apakah user adalah admin
        if ($isDemo) {
            $isAdmin = ($demoRole === 'admin');
        } else {
            $isAdmin = (Auth::user()->role === 'admin');
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F8F6A] px-4 py-3 rounded-lg relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-[#E5E7EB]">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Pemasok</h3>
                    @if ($isAdmin)
                        <a href="{{ route('suppliers.create') }}" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                            + Tambah Pemasok
                        </a>
                    @endif
                </div>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('suppliers.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemasok (nama, kontak)..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-6 py-2 rounded-r-xl font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E5E7EB]">
                        <thead class="bg-[#E9F6F1]">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">No.</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Nama Pemasok</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Kontak</th>
                                <th class="py-3 px-4 text-center text-xs font-semibold text-[#1F8F6A] uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($suppliers as $supplier)
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-3 px-4 text-slate-900">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 font-medium text-slate-900">{{ $supplier->name }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $supplier->contact ?? '-' }}</td>

                                    <td class="py-3 px-4 flex justify-center gap-1">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pemasok ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-50 text-red-700 hover:bg-red-100 transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 px-6 text-center text-slate-600">Belum ada data pemasok yang dicatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>






