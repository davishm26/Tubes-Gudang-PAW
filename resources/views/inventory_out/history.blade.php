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

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Laporan Pengeluaran Barang</h3>
                    </div>

                    <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-100">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Produk</th>
                                    <th class="px-6 py-3 text-center">Jumlah</th>
                                    <th class="px-6 py-3">Petugas</th>
                                    <th class="px-6 py-3">Tujuan / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inventoryOuts as $item)
                                <tr class="bg-white border-b hover:bg-gray-50 transition duration-150">
                                    {{-- Tanggal (Format Indonesia: 15 Des 2025) --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                        <div class="text-xs text-gray-400">
                                            {{ isset($item->created_at) ? \Carbon\Carbon::parse($item->created_at)->format('H:i') : '00:00' }} WIB
                                        </div>
                                    </td>

                                    {{-- Produk --}}
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                        <div class="text-xs text-gray-500">SKU: -</div>
                                    </td>

                                    {{-- Jumlah (Bold Merah) --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">
                                            - {{ number_format($item->quantity) }}
                                        </span>
                                    </td>

                                    {{-- Admin / User --}}
                                    <td class="px-6 py-4 text-xs text-gray-600">
                                        {{ $item->user->name ?? 'Sistem' }}
                                    </td>

                                    {{-- Keterangan --}}
                                    <td class="px-6 py-4 text-gray-500 italic">
                                        {{ $item->description ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400 flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Belum ada riwayat pengeluaran barang.
                                    </td>
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
