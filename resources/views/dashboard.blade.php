<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Gudang') }}
        </h2>
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
                                backgroundColor: 'rgba(59, 130, 246, 0.8)', // blue-500
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Stok Keluar',
                                data: chartData.data_out,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)', // red-500
                                borderColor: 'rgba(239, 68, 68, 1)',
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Selamat Datang, {{ isset($demoUser) ? $demoUser->name : (Auth::user()->name ?? 'Demo User') }}!</h2>

                {{-- ---------------------------------------------------- --}}
                {{-- 1. STATISTIK RINGKASAN (CARDS) --}}
                {{-- ---------------------------------------------------- --}}
                <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Status Gudang Saat Ini</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    {{-- Card Total Produk --}}
                    <div class="bg-blue-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Total Produk</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalProducts }}</p>
                    </div>

                    {{-- Card Total Stok --}}
                    <div class="bg-indigo-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Stok Total (Unit)</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($totalStock, 0, ',', '.') }}</p>
                    </div>

                    {{-- Card Total Supplier --}}
                    <div class="bg-green-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Total Pemasok</p>
                        <p class="text-3xl font-bold mt-1">{{ $totalSuppliers }}</p>
                    </div>

                    {{-- Card Low Stock --}}
                    <div class="bg-red-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Produk Stok Rendah</p>
                        <p class="text-3xl font-bold mt-1">{{ $lowStockCount }}</p>
                    </div>
                </div>

                {{-- ---------------------------------------------------- --}}
                {{-- 2. GRAFIK PERGERAKAN STOK --}}
                {{-- ---------------------------------------------------- --}}
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-8">
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
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-red-700 border-b pb-2 flex justify-between items-center">
                            ⚠️ Notifikasi Stok Kritis
                            <span class="text-sm font-normal text-gray-500">({{ $lowStockProducts->count() }} Item)</span>
                        </h4>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-red-100">
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
                                        <td class="px-6 py-4 text-red-600 font-bold">{{ $product->stock }} unit</td>
                                        <td class="px-6 py-4">
                                            @if ($product->stock === 0)
                                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold">❌ HABIS</span>
                                            @elseif ($product->stock < 5)
                                                <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-xs font-semibold">🔴 SANGAT KRITIS</span>
                                            @else
                                                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold">🟡 KRITIS</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-4 text-center">🎉 Semua stok dalam kondisi aman!</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- B. Aktivitas Gudang Terbaru --}}
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Aktivitas Terbaru</h4>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Waktu</th>
                                        <th scope="col" class="px-6 py-3">Aksi</th>
                                        <th scope="col" class="px-6 py-3">Produk & Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentActivities as $activity)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($activity->date)->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold {{ $activity->type === 'Masuk' ? 'text-blue-600' : 'text-red-600' }}">
                                                {{ $activity->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $activity->quantity }} unit {{ $activity->product->name ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-4 text-center">Belum ada aktivitas transaksi.</td>
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
