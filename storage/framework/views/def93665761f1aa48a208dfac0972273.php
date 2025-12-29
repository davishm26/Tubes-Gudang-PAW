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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tenants</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="<?php echo e(route('super_admin.tenants.create')); ?>" class="mb-4 inline-block bg-blue-600 text-white px-3 py-2 rounded">Create Tenant</a>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3">Subscription</th>
                            <th class="px-6 py-3">Sisa Waktu</th>
                            <th class="px-6 py-3">Suspended</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4"><?php echo e($c->name); ?></td>
                                <td class="px-6 py-4"><?php echo e($c->subscription_status); ?></td>
                                <td class="px-6 py-4">
                                    <?php if($c->subscription_expires_at): ?>
                                        <?php
                                            $daysLeft = now()->diffInDays($c->subscription_expires_at, false);
                                        ?>
                                        <?php if($daysLeft > 0): ?>
                                            <?php echo e($daysLeft); ?> hari
                                        <?php elseif($daysLeft == 0): ?>
                                            Hari ini
                                        <?php else: ?>
                                            Kadaluarsa <?php echo e(abs($daysLeft)); ?> hari yang lalu
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Tidak ada batas
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4"><?php echo e($c->suspended ? 'Yes' : 'No'); ?></td>
                                <td class="px-6 py-4">
                                    <a href="<?php echo e(route('super_admin.tenants.edit', $c)); ?>" class="text-indigo-600 mr-2">Edit</a>
                                    <form action="<?php echo e(route('super_admin.tenants.send-notification', $c)); ?>" method="POST" style="display:inline"><?php echo csrf_field(); ?><button class="text-blue-600 ml-2">Kirim Notifikasi</button></form>
                                    <?php if(!$c->suspended): ?>
                                        <form action="<?php echo e(route('super_admin.tenants.suspend', $c)); ?>" method="POST" style="display:inline"><?php echo csrf_field(); ?><button class="text-red-600 ml-2">Suspend</button></form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('super_admin.tenants.unsuspend', $c)); ?>" method="POST" style="display:inline"><?php echo csrf_field(); ?><button class="text-green-600 ml-2">Unsuspend</button></form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('super_admin.tenants.destroy', $c)); ?>" method="POST" style="display:inline" onsubmit="return confirm('Delete tenant?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="text-red-800 ml-2">Delete</button></form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/super_admin/tenants/index.blade.php ENDPATH**/ ?>