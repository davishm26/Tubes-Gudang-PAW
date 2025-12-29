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
                <a href="<?php echo e(route('super_admin.tenants.create')); ?>" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Tenant</a>

                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Subscription</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Subscription End Date</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-200 px-6 py-4 text-sm text-gray-900"><?php echo e($c->name); ?></td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($c->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e(ucfirst($c->subscription_status)); ?>

                                    </span>
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm text-gray-900">
                                    <?php
                                        $deadline = $c->subscription_end_date ?? $c->subscription_expires_at;
                                    ?>
                                    <?php if($deadline): ?>
                                        <?php echo e(\Carbon\Carbon::parse($deadline)->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        No deadline set
                                    <?php endif; ?>
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm space-x-2">
                                    <a href="<?php echo e(route('super_admin.tenants.edit', $c)); ?>" class="inline text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form action="<?php echo e(route('super_admin.tenants.send-notification', $c)); ?>" method="POST" style="display:inline"><?php echo csrf_field(); ?><button type="submit" class="inline text-blue-600 hover:text-blue-800 font-medium">Notify</button></form>
                                    <?php if($c->subscription_status !== 'suspended'): ?>
                                        <form action="<?php echo e(route('super_admin.tenants.suspend', $c)); ?>" method="POST" style="display:inline"><?php echo csrf_field(); ?><button type="submit" class="inline text-red-600 hover:text-red-800 font-medium">Suspend</button></form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('super_admin.tenants.destroy', $c)); ?>" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button type="submit" class="inline text-red-600 hover:text-red-800 font-medium">Delete</button></form>
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