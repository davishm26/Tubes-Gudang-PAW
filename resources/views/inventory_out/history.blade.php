<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Stok Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Laporan Pengeluaran Barang</h3>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3">Jumlah Keluar</th>
                                    <th class="px-6 py-3">Tujuan / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventoryOuts as $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $item->product->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-red-600 font-bold">- {{ $item->quantity }}</td>
                                    <td class="px-6 py-4">{{ $item->description ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="4" class="px-6 py-4 text-center">Tidak ada data riwayat.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
