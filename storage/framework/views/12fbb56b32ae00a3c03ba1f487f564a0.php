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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Dashboard Gudang')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('inventoryChart').getContext('2d');
                const chartData = <?php echo json_encode($chartData, 15, 512) ?>;

                const inventoryChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [
                            {
                                label: 'Stok Masuk',
                                data: chartData.data_in,
                                backgroundColor: 'rgba(59, 130, 246, 0.8)', // blue-500
                            },
                            {
                                label: 'Stok Keluar',
                                data: chartData.data_out,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)', // red-500
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                stacked: false
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Selamat Datang, <?php echo e(isset($demoUser) ? $demoUser->name : (Auth::user()->name ?? 'Demo User')); ?>!</h2>

                
                
                
                <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">Status Gudang Saat Ini</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    
                    <div class="bg-blue-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Total Produk</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($totalProducts); ?></p>
                    </div>

                    
                    <div class="bg-indigo-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Stok Total (Unit)</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e(number_format($totalStock, 0, ',', '.')); ?></p>
                    </div>

                    
                    <div class="bg-green-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Total Pemasok</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($totalSuppliers); ?></p>
                    </div>

                    
                    <div class="bg-red-500 text-white p-5 rounded-lg shadow-md">
                        <p class="text-sm uppercase opacity-80">Produk Stok Rendah</p>
                        <p class="text-3xl font-bold mt-1"><?php echo e($lowStockCount); ?></p>
                    </div>
                </div>

                
                
                
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-8">
                    <h4 class="text-lg font-semibold mb-4">Pergerakan Stok (30 Hari Terakhir)</h4>
                    <canvas id="inventoryChart" style="max-height: 400px;"></canvas>
                </div>


                
                
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    
                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-red-700 border-b pb-2 flex justify-between items-center">
                            Notifikasi Stok Kritis
                            <span class="text-sm font-normal text-gray-500">(<?php echo e($lowStockProducts->count()); ?> Item)</span>
                        </h4>

                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-red-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3">Sisa Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="bg-white border-b hover:bg-red-50/50">
                                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo e($product->name); ?></td>
                                        <td class="px-6 py-4 text-red-600 font-bold"><?php echo e($product->stock); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="bg-white border-b">
                                        <td colspan="2" class="px-6 py-4 text-center">🎉 Semua stok dalam kondisi aman!</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4"><?php echo e(\Carbon\Carbon::parse($activity->date)->diffForHumans()); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="font-bold <?php echo e($activity->type === 'Masuk' ? 'text-blue-600' : 'text-red-600'); ?>">
                                                <?php echo e($activity->type); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4"><?php echo e($activity->quantity); ?> unit <?php echo e($activity->product->name ?? '-'); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr class="bg-white border-b">
                                        <td colspan="3" class="px-6 py-4 text-center">Belum ada aktivitas transaksi.</td>
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
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\Tubes paw\TUBES-GUDANG-PAW\Tubes-Gudang-PAW\resources\views/dashboard.blade.php ENDPATH**/ ?>