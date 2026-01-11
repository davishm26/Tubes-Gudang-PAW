<x-app-layout>
    <x-slot name="title">Laporan Keuangan - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-white">Laporan Keuangan</h1>
                        <p class="text-sm text-white/80">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
                    <form method="GET" action="{{ route('super_admin.financial-report') }}" id="filterForm" class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-end sm:gap-4">
                        <div class="w-full sm:w-44 space-y-1">
                            <label for="start_date" class="block text-xs font-semibold text-slate-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-[#1F8F6A] focus:ring-2 focus:ring-[#1F8F6A]/20" />
                        </div>
                        <div class="w-full sm:w-44 space-y-1">
                            <label for="end_date" class="block text-xs font-semibold text-slate-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-[#1F8F6A] focus:ring-2 focus:ring-[#1F8F6A]/20" />
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[#166B50] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#0F4C37] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">
                            Terapkan
                        </button>
                    </form>

                    <form method="POST" action="{{ route('super_admin.financial-report.download') }}" class="inline-flex">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-[#1F8F6A] bg-white px-4 py-2 text-sm font-semibold text-[#166B50] shadow-sm hover:bg-[#E9F6F1] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">
                            Unduh PDF
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 bg-[#E9F6F1] px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Total Pendapatan Langganan</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Transaksi Langganan</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($subscriptionTransactions) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pelanggan Aktif</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($activeSubscribers) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">ARPU</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900">Rp {{ number_format($arpu, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700">Pendapatan Langganan per Hari</p>
                    </div>
                    <div class="h-72">
                        <canvas id="revenueChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    Catatan: Semua angka hanya berasal dari langganan. Tidak termasuk biaya, laba, maupun pajak.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = {!! json_encode($revenueTrend->pluck('day')->toArray()) !!};
            const data = {!! json_encode($revenueTrend->pluck('total')->toArray()) !!};

            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;

            // Jika tidak ada data, tampilkan pesan
            if (!labels || labels.length === 0 || !data || data.length === 0) {
                ctx.parentElement.parentElement.innerHTML = '<div class="flex items-center justify-center h-72"><p class="text-slate-500 text-center">Tidak ada data langganan untuk periode ini</p></div>';
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Langganan',
                        data: data,
                        fill: true,
                        borderColor: '#1F8F6A',
                        backgroundColor: 'rgba(31, 143, 106, 0.12)',
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#1F8F6A',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 13, weight: '500' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y || 0;
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: true },
                            ticks: { font: { size: 12 } }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact', compactDisplay: 'short' }).format(value);
                                },
                                font: { size: 12 }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>






