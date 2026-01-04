<x-app-layout>
    <x-slot name="title">Manajemen Pemasok - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Manajemen Pemasok') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Tombol untuk Mencatat Baru (Hanya Admin) --}}
                @php($currentUser = $demoUser ?? Auth::user())
                @if (!session('demo_mode') && $currentUser && $currentUser->role === 'admin')
                    <a href="{{ route('suppliers.create') }}"
                       class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl mb-4 inline-block shadow-md font-semibold transition">
                        + Tambah Pemasok
                    </a>
                @endif

                {{-- Pesan Sukses/Error --}}
                @if (session('success'))
                    <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F8F6A] px-4 py-3 rounded-lg relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-lg relative mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('suppliers.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pemasok atau kontak..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#1F8F6A]">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-r-lg font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-[#E9F6F1]">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">No.</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">Nama Pemasok</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">Kontak</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($suppliers as $supplier)
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-4 px-6 whitespace-nowrap text-slate-900">{{ $loop->iteration }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap font-medium text-slate-900">{{ $supplier->name }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap text-sm text-slate-600">{{ $supplier->contact ?? '-' }}</td>

                                    <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-[#1F8F6A] hover:text-[#166B50] font-semibold mr-3">Edit</a>

                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus pemasok ini?')" class="text-rose-600 hover:text-rose-900 font-semibold">Hapus</button>
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






