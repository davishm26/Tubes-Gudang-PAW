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
        // Pertahankan collection asli; jika array, bungkus ke collection tanpa mengubah model
        $notificationsList = $notifications instanceof \Illuminate\Support\Collection
            ? $notifications
            : collect($notifications);
        $unreadCount = $notificationsList->whereNull('read_at')->count();
    ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-white"><?php echo e(__('Notifikasi')); ?></h1>
                    <div class="flex items-center gap-2">
                        <?php if($isDemo): ?>
                            <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-sm font-medium">
                                Demo Mode
                            </span>
                        <?php endif; ?>
                        <span class="flex items-center gap-2 text-sm text-white/90">
                            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white border border-white/20">
                                <span class="h-2 w-2 rounded-full <?php echo e($unreadCount ? 'bg-red-400' : 'bg-green-400'); ?>"></span>
                                <?php echo e($unreadCount); ?> <?php echo e(__('belum dibaca')); ?>

                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if($isDemo): ?>
                <div class="mb-6 p-4 bg-[#E9F6F1] border border-[#C8E6DF] rounded-lg">
                    <p class="text-sm text-[#166B50]">
                        <strong>Demo Mode:</strong> Anda sedang melihat notifikasi demo. Total <?php echo e($notificationsList->count()); ?> notifikasi contoh.
                    </p>
                </div>
            <?php endif; ?>

            <?php if($notificationsList->isEmpty()): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold"><?php echo e(__('Belum ada notifikasi untuk Anda.')); ?></p>
                        <p class="text-sm text-gray-500 mt-2"><?php echo e(__('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.')); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $notificationsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isRead = (data_get($notification, 'read_at')) ? true : false;
                            $notificationId = data_get($notification, 'id');
                        ?>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border <?php echo e($isRead ? 'border-gray-200' : 'border-[#C8E6DF]'); ?>">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($isRead ? 'bg-gray-100 text-gray-600' : 'bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]'); ?>">
                                            <?php echo e($isRead ? 'Dibaca' : 'Belum dibaca'); ?>

                                        </span>
                                        <?php if(data_get($notification, 'type') || data_get($notification, 'template')): ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                <?php echo e(data_get($notification, 'type', data_get($notification, 'template')) === 'success' ? 'bg-green-100 text-green-700' : ''); ?>

                                                <?php echo e(data_get($notification, 'type', data_get($notification, 'template')) === 'warning' ? 'bg-yellow-100 text-yellow-700' : ''); ?>

                                                <?php echo e(data_get($notification, 'type', data_get($notification, 'template')) === 'info' ? 'bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]' : ''); ?>

                                                <?php echo e((data_get($notification, 'template') && !data_get($notification, 'type')) ? 'bg-slate-100 text-slate-700 border border-slate-200' : ''); ?>">
                                                <?php if(data_get($notification, 'type')): ?>
                                                    <?php echo e(ucfirst(data_get($notification, 'type'))); ?>

                                                <?php elseif(data_get($notification, 'template')): ?>
                                                    <?php echo e(ucwords(str_replace('_', ' ', data_get($notification, 'template')))); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if(data_get($notification, 'title')): ?>
                                        <p class="text-gray-900 font-semibold leading-relaxed"><?php echo e(data_get($notification, 'title')); ?></p>
                                        <p class="text-gray-700"><?php echo e(data_get($notification, 'message', '')); ?></p>
                                    <?php else: ?>
                                        <p class="text-gray-900 font-semibold leading-relaxed"><?php echo e(data_get($notification, 'message', data_get($notification, 'message')) ?? 'Notifikasi'); ?></p>
                                    <?php endif; ?>

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <?php if(data_get($notification, 'user_name')): ?>
                                            <span><?php echo e(__('Dari')); ?>: <?php echo e(data_get($notification, 'user_name')); ?></span>
                                        <?php else: ?>
                                            <span><?php echo e(__('Dari')); ?>: <?php echo e(data_get($notification, 'sender.name') ?? 'System'); ?></span>
                                        <?php endif; ?>
                                        <span class="text-gray-300">•</span>
                                        <?php if(data_get($notification, 'created_at')): ?>
                                            <span><?php echo e(\Carbon\Carbon::parse(data_get($notification, 'created_at'))->locale(config('app.locale'))->diffForHumans()); ?></span>
                                        <?php else: ?>
                                            <span><?php echo e(optional($notification->created_at)->locale(config('app.locale'))->diffForHumans()); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if(!$isDemo): ?>
                                    <div class="flex items-center gap-3">
                                        <?php if(!$isRead && $notificationId): ?>
                                            <form method="POST" action="<?php echo e(route('notifications.markAsRead', $notificationId)); ?>">
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
                                        <?php elseif($isRead): ?>
                                            <span class="text-xs text-gray-500"><?php echo e(__('Ditandai dibaca pada')); ?>

                                                <?php if(data_get($notification, 'read_at')): ?>
                                                    <?php echo e(\Carbon\Carbon::parse(data_get($notification, 'read_at'))->format('d M Y, H:i')); ?>

                                                <?php else: ?>
                                                    <?php echo e(optional($notification->read_at)->format('d M Y, H:i')); ?>

                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-red-600">ID notifikasi tidak ditemukan.</span>
                                        <?php endif; ?>

                                        <?php if((data_get($notification, 'template') === 'subscription_expiry' || (isset($notification->template) && $notification->template === 'subscription_expiry')) && Auth::user()?->role === 'admin'): ?>
                                            <a
                                                href="<?php echo e(route('subscription.subscribe')); ?>"
                                                class="inline-flex items-center rounded-md bg-[#166B50] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#0F4C37] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2"
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