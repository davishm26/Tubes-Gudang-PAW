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

                    {{-- Tombol Tambah Stok Keluar --}}
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Barang Keluar</h3>
                        <a href="{{ route('inventory-out.create') }}" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            - Catat Stok Keluar
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
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Tujuan/Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop Data (Pastikan Controller mengirim variabel $inventoryOuts) --}}
                                @forelse ($inventoryOuts ?? [] as $index => $item)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </td>
                                    <td class="px-6 py-4 text-red-600 font-bold">
                                        -{{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4">{{ $item->description ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white border-b">
                                    <td colspan="5" class="px-6 py-4 text-center">Belum ada data stok keluar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                     {{-- Pagination (Jika ada) --}}
                     <div class="mt-4">
                        {{-- {{ $inventoryOuts->links() }} --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
