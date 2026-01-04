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
     <?php $__env->slot('title', null, []); ?> Dashboard Super Admin - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-900 leading-tight"><?php echo e(__('Beranda Super Admin')); ?></h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Statistik Global</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Tenant:</strong>
                        <div class="text-2xl"><?php echo e($totalTenants); ?></div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Pengguna:</strong>
                        <div class="text-2xl"><?php echo e($allUsers->count()); ?></div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Pendapatan Langganan:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold mb-4">Tenant</h4>
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden" style="table-layout: fixed;">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Nama</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Langganan</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Pengguna</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-emerald-50/30 transition">
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 w-1/3"><?php echo e($t->name); ?></td>
                                    <td class="px-6 py-4 text-center text-sm w-1/3">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($t->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($t->subscription_status === 'active' ? 'Aktif' : 'Tersuspend'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 font-medium w-1/3"><?php echo e($t->users->count()); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/super_admin/dashboard.blade.php ENDPATH**/ ?>