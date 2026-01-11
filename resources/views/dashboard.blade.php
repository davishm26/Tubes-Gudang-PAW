<x-app-layout>
    <x-slot name="title">Dashboard - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white">Selamat Datang, {{ isset($demoUser) ? $demoUser->name : (Auth::user()->name ?? 'Demo User') }}!</h1>
                <p class="text-white/90 mt-2">Status Gudang Saat Ini</p>
            </div>
        </div>
    </x-slot>

    {{-- Script untuk Chart.js (HARUS ADA DI LAYOUT ATAU DI SINI) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('inventoryChart');
                if (!ctx) return;

                const chartData = @json($chartData);

                console.log('Chart Data:', chartData); // Debug

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Stok Masuk',
                                data: chartData.data_in,
                                backgroundColor: 'rgba(16, 185, 129, 0.8)', // emerald-500
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Stok Keluar',
                                data: chartData.data_out,
                                backgroundColor: 'rgba(245, 158, 11, 0.8)', // amber-400
                                borderColor: 'rgba(245, 158, 11, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                stacked: false,
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                stacked: false
                            }
                        }
                    }
                });
            });
        </script>
    @endpush


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                {{-- ---------------------------------------------------- --}}
                {{-- 1. STATISTIK RINGKASAN (CARDS) --}}
                {{-- ---------------------------------------------------- --}}

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    {{-- Card Total Produk --}}
                    <div class="bg-gradient-to-br from-[#1F8F6A] to-[#166B50] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Total Produk</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalProducts }}</p>
                    </div>

                    {{-- Card Total Stok --}}
                    <div class="bg-gradient-to-br from-[#1F8F6A] to-[#166B50] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Stok Total (Unit)</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalStock, 0, ',', '.') }}</p>
                    </div>

                    {{-- Card Total Supplier --}}
                    <div class="bg-gradient-to-br from-[#10B981] to-[#059669] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Total Pemasok</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalSuppliers }}</p>
                    </div>

                    {{-- Card Low Stock --}}
                    <div class="bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Produk Stok Rendah</p>
                        <p class="text-3xl font-bold mt-1">{{ $lowStockCount }}</p>
                    </div>
                </div>

                {{-- ---------------------------------------------------- --}}
                {{-- 2. GRAFIK PERGERAKAN STOK --}}
                {{-- ---------------------------------------------------- --}}
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h4 class="text-lg font-semibold mb-4">Pergerakan Stok (7 Hari Terakhir)</h4>
                    <div style="height: 400px; position: relative;">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>


                {{-- ---------------------------------------------------- --}}
                {{-- 3. TABEL NOTIFIKASI DAN AKTIVITAS --}}
                {{-- ---------------------------------------------------- --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- A. Notifikasi Stok Rendah --}}
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center justify-between mb-4 border-b pb-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 ring-1 ring-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.1 19h13.8a1 1 0 00.9-1.45L13.9 4.55a1 1 0 00-1.8 0L4.2 17.55A1 1 0 005.1 19z" />
                                    </svg>
                                </span>
                                <h4 class="text-lg font-semibold text-red-700">Notifikasi Stok Kritis</h4>
                            </div>
                            <span class="text-sm font-normal text-gray-500">({{ $lowStockProducts->count() }} Item)</span>
                        </div>

                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="text-xs uppercase bg-red-50 text-red-700 border-b border-red-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3">Sisa Stok</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($lowStockProducts as $product)
                                    <tr class="bg-white border-b hover:bg-red-50/50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-red-600 font-semibold">{{ $product->stock }} unit</td>
                                        <td class="px-6 py-4">
                                            @if ($product->stock === 0)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">Habis</span>
                                            @elseif ($product->stock < 5)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700">Sangat Kritis</span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">Kritis</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">Tidak ada stok kritis. Semua aman.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- B. Aktivitas Gudang Terbaru --}}
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center justify-between mb-4 border-b pb-3">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 ring-1 ring-blue-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                        <circle cx="12" cy="12" r="9" />
                                    </svg>
                                </span>
                                <h4 class="text-lg font-semibold text-gray-700">Aktivitas Terbaru</h4>
                            </div>
                        </div>

                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700">
                                <thead class="text-xs uppercase bg-blue-50 text-blue-700 border-b border-blue-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Waktu</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                        <th scope="col" class="px-6 py-3">Produk & Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActivities as $activity)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $activity->type === 'Masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                                {{ $activity->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-900">{{ $activity->quantity }} unit {{ $activity->product->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">Belum ada aktivitas transaksi.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>






