<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Laporan Keuangan - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-white">Financial Report</h1>
                        <p class="text-sm text-white/80">Period: <?php echo e(\Carbon\Carbon::parse($startDate)->format('Y-m-d')); ?> â€" <?php echo e(\Carbon\Carbon::parse($endDate)->format('Y-m-d')); ?></p>
                    </div>
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="space-y-1">
                    <label for="start_date" class="block text-xs font-semibold text-slate-600">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="<?php echo e($startDate); ?>" form="filterForm" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" />
                </div>
                <div class="space-y-1">
                    <label for="end_date" class="block text-xs font-semibold text-slate-600">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="<?php echo e($endDate); ?>" form="filterForm" class="rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100" />
                </div>
                <form method="GET" action="<?php echo e(route('super_admin.financial-report')); ?>" id="filterForm" class="inline-flex">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">Terapkan</button>
                </form>
                <form method="POST" action="<?php echo e(route('super_admin.financial-report.download')); ?>" class="inline-flex">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e($startDate); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e($endDate); ?>">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-[#1F8F6A] px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#166B50] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">Download PDF</button>
                </form>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6 space-y-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Subscription Revenue</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">Rp <?php echo e(number_format($subscriptionRevenue, 0, ',', '.')); ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Subscription Transactions</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900"><?php echo e(number_format($subscriptionTransactions)); ?></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-4 py-5 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Subscribers</p>
                        <p class="mt-2 text-2xl font-semibold text-slate-900"><?php echo e(number_format($activeSubscribers)); ?></p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-700">Subscription Revenue Over Time</p>
                    </div>
                    <div class="h-72">
                        <canvas id="revenueChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                    All revenue shown is from subscriptions. No expenses, profit, or tax breakdown included.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = <?php echo json_encode($revenueTrend->pluck('day')->toArray()); ?>;
            const data = <?php echo json_encode($revenueTrend->pluck('total')->toArray()); ?>;

            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;

            // Jika tidak ada data, tampilkan pesan
            if (!labels || labels.length === 0 || !data || data.length === 0) {
                ctx.parentElement.parentElement.innerHTML = '<div class="flex items-center justify-center h-72"><p class="text-slate-500 text-center">Tidak ada data subscription untuk periode ini</p></div>';
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Subscription Revenue',
                        data: data,
                        fill: true,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#4f46e5',
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/super_admin/financial_report.blade.php ENDPATH**/ ?>