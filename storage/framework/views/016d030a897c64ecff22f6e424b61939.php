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
        <h1 class="text-xl font-semibold text-gray-800">Notifikasi</h1>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if($notifications->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white shadow-md rounded-lg p-6 <?php echo e($notification->isRead() ? 'border-l-4 border-green-500' : 'border-l-4 border-blue-500'); ?>">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-gray-800 mb-2"><?php echo e($notification->message); ?></p>
                                    <p class="text-sm text-gray-500">
                                        Dari: <?php echo e($notification->sender->name); ?> |
                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                    </p>
                                    <?php if($notification->template): ?>
                                        <p class="text-xs text-gray-400 mt-1">Template: <?php echo e($notification->template); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if(!$notification->isRead()): ?>
                                    <form action="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>" method="POST" class="ml-4">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Tandai Dibaca
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500">Tidak ada notifikasi.</p>
            <?php endif; ?>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/notifications/index.blade.php ENDPATH**/ ?>