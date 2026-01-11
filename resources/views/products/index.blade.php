<x-app-layout>
    <x-slot name="title">Manajemen Produk - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">{{ __('Manajemen Produk') }}</h2>
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
                    <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F2937] px-4 py-3 rounded-lg relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-[#E5E7EB]">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Produk</h3>
                    @if ($isAdmin)
                        <a href="{{ route('products.create') }}" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                            + Tambah Produk Baru
                        </a>
                    @endif
                </div>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk (nama, SKU, kategori, pemasok)..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
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
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Gambar</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Nama & SKU</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Kategori</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Pemasok</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Stok</th>
                                @if ($isAdmin)
                                    <th class="py-3 px-4 text-center text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($products as $product)
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-3 px-4">
                                        @if($product->image)
                                            @php
                                                // Jika URL absolut (http/https), gunakan langsung
                                                $imageUrl = (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://'))
                                                    ? $product->image
                                                    : Storage::url($product->image);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded border border-gray-200">
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak Ada Gambar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $product->name }}</div>
                                        <div class="text-xs text-slate-600">SKU: {{ $product->sku ?? $product->code ?? '-' }}</div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $product->supplier->name ?? '-' }}</td>
                                    <td class="py-3 px-4 font-semibold
                                        {{ $product->stock < 10 ? 'text-rose-600' : 'text-[#1F8F6A]' }}">
                                        {{ number_format($product->stock) }}
                                    </td>

                                    @if ($isAdmin)
                                        <td class="py-3 px-4 flex justify-center gap-1">
                                            <a href="{{ route('products.edit', $product->id) }}" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                                            {{-- Tombol Hapus - Sembunyikan jika staff di mode demo --}}
                                            @if (!$isDemo || $demoRole !== 'staff')
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-50 text-red-700 hover:bg-red-100 transition">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">Belum ada data produk yang dicatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>






