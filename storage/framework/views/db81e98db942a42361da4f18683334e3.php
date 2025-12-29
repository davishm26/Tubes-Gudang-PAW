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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"><?php echo e(__('Super Admin Dashboard')); ?></h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Global Statistics</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Tenants:</strong>
                        <div class="text-2xl"><?php echo e($totalTenants); ?></div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Users:</strong>
                        <div class="text-2xl"><?php echo e($allUsers->count()); ?></div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Revenue (dummy):</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold">Tenants</h4>
                    <table class="min-w-full mt-2 border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2">Subscription</th>
                                <th class="p-2">Suspended</th>
                                <th class="p-2">Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-t">
                                    <td class="p-2"><?php echo e($t->name); ?></td>
                                    <td class="p-2"><?php echo e($t->subscription_status); ?></td>
                                    <td class="p-2 text-center"><?php echo e($t->suspended ? 'Yes' : 'No'); ?></td>
                                    <td class="p-2"><?php echo e($t->users->count()); ?></td>
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
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\Tubes paw\TUBES-GUDANG-PAW\Tubes-Gudang-PAW\resources\views/super_admin/dashboard.blade.php ENDPATH**/ ?>