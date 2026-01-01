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
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800"><?php echo e(__('Notifikasi')); ?></h1>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                    <span class="h-2 w-2 rounded-full <?php echo e($notifications->whereNull('read_at')->count() ? 'bg-red-500' : 'bg-green-500'); ?>"></span>
                    <?php echo e($notifications->whereNull('read_at')->count()); ?> <?php echo e(__('belum dibaca')); ?>

                </span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if($notifications->isEmpty()): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold"><?php echo e(__('Belum ada notifikasi untuk Anda.')); ?></p>
                        <p class="text-sm text-gray-500 mt-2"><?php echo e(__('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border <?php echo e($notification->read_at ? 'border-gray-200' : 'border-indigo-200'); ?>">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($notification->read_at ? 'bg-gray-100 text-gray-600' : 'bg-indigo-50 text-indigo-700 border border-indigo-200'); ?>">
                                            <?php echo e($notification->read_at ? __('Sudah dibaca') : __('Belum dibaca')); ?>

                                        </span>
                                        <?php if($notification->template): ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                                <?php echo e(ucwords(str_replace('_', ' ', $notification->template))); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <p class="text-gray-900 font-semibold leading-relaxed"><?php echo e($notification->message); ?></p>

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <span><?php echo e(__('Dari')); ?>: <?php echo e(optional($notification->sender)->name ?? 'System'); ?></span>
                                        <span class="text-gray-300">•</span>
                                        <span><?php echo e($notification->created_at->diffForHumans()); ?></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <?php if(!$notification->read_at): ?>
                                        <form method="POST" action="<?php echo e(route('notifications.markAsRead', $notification->id)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(__('Tandai dibaca')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-500"><?php echo e(__('Ditandai dibaca pada')); ?> <?php echo e($notification->read_at->format('d M Y, H:i')); ?></span>
                                    <?php endif; ?>

                                    <?php if($notification->template === 'subscription_expiry' && Auth::user()?->role === 'admin'): ?>
                                        <a
                                            href="<?php echo e(route('subscription.subscribe')); ?>"
                                            class="inline-flex items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Perpanjang Langganan
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
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