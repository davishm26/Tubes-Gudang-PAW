<x-app-layout>
    <x-slot name="title">Barang Keluar - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Riwayat Stok Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">

                    {{-- Tombol Tambah Stok Keluar --}}
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-slate-900">Daftar Barang Keluar</h3>
                        <a href="{{ route('inventory-out.create') }}" class="px-4 py-2 bg-rose-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 focus:outline-none focus:ring ring-rose-300 disabled:opacity-25 transition ease-in-out duration-150">
                            - Catat Stok Keluar
                        </a>
                    </div>

                    {{-- Tabel Riwayat --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-[#166B50] uppercase bg-[#E9F6F1] font-semibold">
                                <tr>
                                    <th scope="col" class="px-6 py-3">No</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Produk</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Tujuan/Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                {{-- Loop Data (Pastikan Controller mengirim variabel $inventoryOuts) --}}
                                @forelse ($inventoryOuts ?? [] as $index => $item)
                                <tr class="bg-white hover:bg-[#E9F6F1]/30 transition">
                                    <td class="px-6 py-4 text-slate-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-slate-900">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $item->product->name ?? 'Produk Dihapus' }}
                                    </td>
                                    <td class="px-6 py-4 text-rose-600 font-bold">
                                        -{{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-700">{{ $item->description ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr class="bg-white">
                                    <td colspan="5" class="px-6 py-4 text-center text-slate-600">Belum ada data stok keluar.</td>
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






