<x-app-layout>
    <x-slot name="title">Manajemen Produk - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
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

                {{-- Tombol untuk Mencatat Baru (Hanya Admin) --}}
                @if ($isAdmin)
                    <a href="{{ route('products.create') }}"
                       class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl mb-4 inline-block shadow-md font-semibold transition">
                        + Tambah Produk Baru
                    </a>
                @endif

                {{-- Pesan Sukses/Error --}}
                @if (session('success'))
                    <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F2937] px-4 py-3 rounded-lg relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3 rounded-lg relative mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('products.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk, SKU, kategori, atau pemasok..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-lg focus:outline-none focus:ring-2 focus:ring-[#1F8F6A]">
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
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Gambar</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Nama & SKU</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Kategori</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Pemasok</th>
                                <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Stok</th>
                                @if ($isAdmin)
                                    <th class="py-3 px-6 text-left text-xs font-semibold text-[#166B50] uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="py-4 px-6 whitespace-nowrap">
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
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-500">SKU: {{ $product->sku ?? $product->code ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">{{ $product->category->name ?? '-' }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap">{{ $product->supplier->name ?? '-' }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap font-bold
                                        {{ $product->stock < 10 ? 'text-red-500' : 'text-green-600' }}">
                                        {{ number_format($product->stock) }}
                                    </td>

                                    @if ($isAdmin)
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                            {{-- Tombol Hapus - Sembunyikan jika staff di mode demo --}}
                                            @if (!$isDemo || $demoRole !== 'staff')
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
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






