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
     <?php $__env->slot('title', null, []); ?> Notifikasi - StockMaster <?php $__env->endSlot(); ?>
    <?php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
        // Convert notifications to array if it's a collection
        $notificationsArray = is_array($notifications) ? $notifications : $notifications->toArray();
    ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800"><?php echo e(__('Notifikasi')); ?></h1>
            <div class="flex items-center gap-2">
                <?php if($isDemo): ?>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        Demo Mode
                    </span>
                <?php endif; ?>
                <span class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                        <span class="h-2 w-2 rounded-full <?php echo e(collect($notificationsArray)->whereNull('read_at')->count() ? 'bg-red-500' : 'bg-green-500'); ?>"></span>
                        <?php echo e(collect($notificationsArray)->whereNull('read_at')->count()); ?> <?php echo e(__('belum dibaca')); ?>

                    </span>
                </span>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if($isDemo): ?>
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Demo Mode:</strong> Anda sedang melihat notifikasi demo. Total <?php echo e(count($notificationsArray)); ?> notifikasi contoh.
                    </p>
                </div>
            <?php endif; ?>

            <?php if(empty($notificationsArray)): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold"><?php echo e(__('Belum ada notifikasi untuk Anda.')); ?></p>
                        <p class="text-sm text-gray-500 mt-2"><?php echo e(__('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $notificationsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isRead = ($notification['read_at'] ?? $notification->read_at ?? null) ? true : false;
                        ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border <?php echo e($isRead ? 'border-gray-200' : 'border-indigo-200'); ?>">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($isRead ? 'bg-gray-100 text-gray-600' : 'bg-indigo-50 text-indigo-700 border border-indigo-200'); ?>">
                                            <?php echo e($isRead ? 'Dibaca' : 'Belum dibaca'); ?>

                                        </span>
                                        <?php if(isset($notification['type']) || isset($notification->template)): ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                <?php echo e(($notification['type'] ?? $notification->template ?? null) === 'success' ? 'bg-green-100 text-green-700' : ''); ?>

                                                <?php echo e(($notification['type'] ?? $notification->template ?? null) === 'warning' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                                                <?php echo e(($notification['type'] ?? $notification->template ?? null) === 'info' ? 'bg-blue-100 text-blue-700' : ''); ?>

                                                <?php echo e(isset($notification->template) && !isset($notification['type']) ? 'bg-slate-100 text-slate-700 border border-slate-200' : ''); ?>">
                                                <?php if(isset($notification['type'])): ?>
                                                    <?php echo e(ucfirst($notification['type'])); ?>

                                                <?php elseif(isset($notification->template)): ?>
                                                    <?php echo e(ucwords(str_replace('_', ' ', $notification->template))); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(isset($notification['title'])): ?>
                                        <p class="text-gray-900 font-semibold leading-relaxed"><?php echo e($notification['title']); ?></p>
                                        <p class="text-gray-700"><?php echo e($notification['message'] ?? ''); ?></p>
                                    <?php else: ?>
                                        <p class="text-gray-900 font-semibold leading-relaxed"><?php echo e($notification['message'] ?? $notification->message ?? 'Notifikasi'); ?></p>
                                    <?php endif; ?>

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <?php if(isset($notification['user_name'])): ?>
                                            <span><?php echo e(__('Dari')); ?>: <?php echo e($notification['user_name']); ?></span>
                                        <?php else: ?>
                                            <span><?php echo e(__('Dari')); ?>: <?php echo e(optional($notification->sender ?? null)->name ?? 'System'); ?></span>
                                        <?php endif; ?>
                                        <span class="text-gray-300">•</span>
                                        <?php if(isset($notification['created_at'])): ?>
                                            <span><?php echo e(\Carbon\Carbon::parse($notification['created_at'])->diffForHumans()); ?></span>
                                        <?php else: ?>
                                            <span><?php echo e($notification->created_at->diffForHumans()); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if(!$isDemo): ?>
                                    <div class="flex items-center gap-3">
                                        <?php if(!$isRead): ?>
                                            <form method="POST" action="<?php echo e(route('notifications.markAsRead', $notification->id ?? $notification['id'])); ?>">
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
                                            <span class="text-xs text-gray-500"><?php echo e(__('Ditandai dibaca pada')); ?>

                                                <?php if(isset($notification['read_at'])): ?>
                                                    <?php echo e(\Carbon\Carbon::parse($notification['read_at'])->format('d M Y, H:i')); ?>

                                                <?php else: ?>
                                                    <?php echo e($notification->read_at->format('d M Y, H:i')); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>

                                        <?php if((isset($notification['template']) && $notification['template'] === 'subscription_expiry' || isset($notification->template) && $notification->template === 'subscription_expiry') && Auth::user()?->role === 'admin'): ?>
                                            <a
                                                href="<?php echo e(route('subscription.subscribe')); ?>"
                                                class="inline-flex items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                            >
                                                Perpanjang Langganan
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
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