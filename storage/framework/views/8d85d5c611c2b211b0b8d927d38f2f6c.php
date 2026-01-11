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
     <?php $__env->slot('title', null, []); ?> Kirim Notifikasi - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold leading-tight text-white">Kirim Notifikasi</h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="<?php echo e(route('super_admin.notifications.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="recipient_id" class="block text-gray-700 text-sm font-bold mb-2">Penerima:</label>
                        <select name="recipient_id" id="recipient_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Admin Perusahaan</option>
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($company->company): ?>
                                    <option value="<?php echo e($company->id); ?>" <?php if($selectedRecipient && $selectedRecipient->id === $company->id): echo 'selected'; endif; ?>>
                                        <?php echo e($company->company->name); ?> - <?php echo e($company->name); ?>

                                    </option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="template" class="block text-gray-700 text-sm font-bold mb-2">Template:</label>
                        <?php
                            $templateLabels = [
                                'maintenance' => 'Pemeliharaan',
                                'update' => 'Pembaruan Sistem',
                                'reminder' => 'Pengingat',
                                'subscription_expiry' => 'Masa Langganan Hampir Habis',
                                'announcement' => 'Pengumuman',
                            ];
                        ?>
                        <select name="template" id="template" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Template (Opsional)</option>
                            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($templateLabels[$key] ?? ucwords(str_replace('_', ' ', (string) $key))); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Pesan:</label>
                        <textarea name="message" id="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">
                            Kirim Notifikasi
                        </button>
                        <a href="<?php echo e(route('super_admin.dashboard')); ?>" class="inline-block align-baseline font-bold text-sm text-[#1F8F6A] hover:text-[#0F4C37]">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('template').addEventListener('change', function() {
            const selectedTemplate = this.value;
            const messageTextarea = document.getElementById('message');
            const templates = <?php echo json_encode($templates, 15, 512) ?>;

            if (selectedTemplate && templates[selectedTemplate]) {
                messageTextarea.value = templates[selectedTemplate];
            }
        });
    </script>
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






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/notifications/create.blade.php ENDPATH**/ ?>