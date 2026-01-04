<x-app-layout>
    <x-slot name="title">Manajemen Kategori - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-300">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-4 p-3 rounded-lg bg-sky-50 text-sky-800 border border-sky-300">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-800 border border-rose-300">
                        {{ session('error') }}
                    </div>
                @endif

                <a href="{{ route('categories.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg mb-4 inline-block font-semibold transition">Tambah Kategori</a>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('categories.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="flex-1 px-4 py-2 border border-emerald-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-r-lg font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <table class="w-full text-left border-collapse">
                    <thead class="bg-emerald-50">
                        <tr>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">No.</th>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">Nama Kategori</th>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-emerald-50/30 transition">
                                <td class="py-3 px-4 text-slate-900">{{ $loop->iteration }}</td>
                                <td class="py-3 px-4 text-slate-900 font-medium">{{ $category->name }}</td>
                                <td class="py-3 px-4">
                                    {{-- 1. TOMBOL EDIT --}}
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-emerald-600 hover:text-emerald-900 font-semibold mr-4">
                                        Edit
                                    </a>

                                    {{-- 2. FORM HAPUS --}}
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                        {{-- Token Keamanan Wajib --}}
                                        @csrf

                                        {{-- Menggunakan method DELETE (persyaratan Laravel Resource Controller) --}}
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori: {{ $category->name }}?')"
                                            class="text-rose-600 hover:text-rose-900 font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
