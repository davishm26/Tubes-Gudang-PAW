<x-app-layout>
    <x-slot name="title">Manajemen Kategori - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">{{ __('Manajemen Kategori') }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-[#E9F6F1] text-[#1F8F6A] border border-[#C8E6DF]">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-4 p-3 rounded-lg bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-[#E5E7EB]">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Kategori</h3>
                    <a href="{{ route('categories.create') }}" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                        + Tambah Kategori
                    </a>
                </div>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-6 py-2 rounded-r-xl font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E5E7EB]">
                        <thead class="bg-[#E9F6F1]">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">No.</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Nama Kategori</th>
                                <th class="py-3 px-4 text-center text-xs font-semibold text-[#1F8F6A] uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-3 px-4 text-slate-900">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-4 text-slate-900 font-medium">{{ $category->name }}</td>
                                    <td class="py-3 px-4 flex justify-center gap-1">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori: {{ $category->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-50 text-red-700 hover:bg-red-100 transition">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4" colspan="3">Belum ada kategori.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>






