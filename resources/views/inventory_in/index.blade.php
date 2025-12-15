<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Stok Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol Tambah Stok Masuk --}}
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Barang Masuk</h3>
                        <a href="{{ route('inventory-in.create') }}" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            + Tambah Stok Masuk
                        </a>
                    </div>

                    {{-- Tabel Riwayat --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
