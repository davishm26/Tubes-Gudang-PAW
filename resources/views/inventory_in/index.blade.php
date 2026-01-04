<x-app-layout>
    <x-slot name="title">Barang Masuk - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Riwayat Stok Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 text-slate-900">

                    {{-- Tombol Tambah Stok Masuk --}}
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">Daftar Barang Masuk</h3>
                        <a href="{{ route('inventory-in.create') }}" class="px-4 py-2 bg-[#1F8F6A] border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-[#166B50] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2 transition">
                            + Tambah Stok Masuk
                        </a>
                    </div>

                    {{-- Tabel Riwayat --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-xl">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-[#166B50] uppercase bg-[#E9F6F1] font-semibold">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Produk</th>
                                    <th scope="col" class="px-6 py-3">Pemasok</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop Data (Pastikan Controller mengirim variabel $inventoryIns) --}}
                                @forelse ($inventoryIns ?? [] as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->supplier->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-green-600 font-bold">
                                        +{{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4">{{ $item->description ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="6" class="px-6 py-4 text-center">Belum ada data stok masuk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination (Jika ada) --}}
                    <div class="mt-4">
                        {{-- {{ $inventoryIns->links() }} --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>






