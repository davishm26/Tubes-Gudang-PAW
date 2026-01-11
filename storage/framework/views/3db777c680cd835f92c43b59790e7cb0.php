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
     <?php $__env->slot('title', null, []); ?> Dashboard - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-white">Selamat Datang, <?php echo e(isset($demoUser) ? $demoUser->name : (Auth::user()->name ?? 'Demo User')); ?>!</h1>
                <p class="text-white/90 mt-2">Status Gudang Saat Ini</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('inventoryChart');
                if (!ctx) return;

                const chartData = <?php echo json_encode($chartData, 15, 512) ?>;

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
    <?php $__env->stopPush(); ?>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                
                
                

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    
                    <div class="bg-gradient-to-br from-[#1F8F6A] to-[#166B50] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Total Produk</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($totalProducts); ?></p>
                    </div>

                    
                    <div class="bg-gradient-to-br from-[#1F8F6A] to-[#166B50] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Stok Total (Unit)</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e(number_format($totalStock, 0, ',', '.')); ?></p>
                    </div>

                    
                    <div class="bg-gradient-to-br from-[#10B981] to-[#059669] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Total Pemasok</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($totalSuppliers); ?></p>
                    </div>

                    
                    <div class="bg-gradient-to-br from-[#F59E0B] to-[#D97706] text-white p-5 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm uppercase opacity-90 font-medium">Produk Stok Rendah</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($lowStockCount); ?></p>
                    </div>
                </div>

                
                
                
                <div class="bg-white p-6 rounded-lg shadow-md mb-8">
                    <h4 class="text-lg font-semibold mb-4">Pergerakan Stok (7 Hari Terakhir)</h4>
                    <div style="height: 400px; position: relative;">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>


                
                
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    
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
                            <span class="text-sm font-normal text-gray-500">(<?php echo e($lowStockProducts->count()); ?> Item)</span>
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
                                    <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="bg-white border-b hover:bg-red-50/50">
                                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($product->name); ?></td>
                                        <td class="px-6 py-4 text-red-600 font-semibold"><?php echo e($product->stock); ?> unit</td>
                                        <td class="px-6 py-4">
                                            <?php if($product->stock === 0): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">Habis</span>
                                            <?php elseif($product->stock < 5): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700">Sangat Kritis</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700">Kritis</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">Tidak ada stok kritis. Semua aman.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-600"><?php echo e(\Carbon\Carbon::parse($activity->date)->diffForHumans()); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($activity->type === 'Masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'); ?>">
                                                <?php echo e($activity->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-900"><?php echo e($activity->quantity); ?> unit <?php echo e($activity->product->name ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-6 text-center text-gray-500">Belum ada aktivitas transaksi.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
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






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/dashboard.blade.php ENDPATH**/ ?>