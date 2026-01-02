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
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            <?php echo e(__('Permintaan Reaktivasi Akun')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <?php if(session('success')): ?>
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
                    <p class="text-sm font-medium text-green-800">✓ <?php echo e(session('success')); ?></p>
                </div>
            <?php endif; ?>

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Daftar Permintaan Reaktivasi</h3>
                            <p class="text-sm text-gray-600 mt-1">Kelola permintaan reaktivasi dari perusahaan yang ter-suspend</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Total: <?php echo e($requests->count()); ?> permintaan
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                Permintaan sudah ditangani: <?php echo e($requests->where('is_read', true)->count()); ?>

                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <?php if($requests->isEmpty()): ?>
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada permintaan</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada permintaan reaktivasi yang masuk</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg <?php echo e(!$request['is_read'] ? 'bg-blue-50 border-blue-300' : ''); ?>">
                                    <!-- Header -->
                                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start gap-3">
                                                <?php if(!$request['is_read']): ?>
                                                    <span class="mt-1 inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-blue-600 text-white">
                                                        BARU
                                                    </span>
                                                <?php endif; ?>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900"><?php echo e($request['company_name']); ?></h4>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        <span class="font-medium">Dikirim:</span>
                                                        <?php echo e($request['created_at']->diffForHumans()); ?>

                                                        (<?php echo e($request['created_at']->format('d M Y H:i')); ?>)
                                                    </p>
                                                </div>
                                            </div>

                                            <?php if($request['company']): ?>
                                                <?php
                                                    $isSuspended = $request['company']->suspended || $request['company']->subscription_status === 'suspended';
                                                ?>
                                                <div class="flex items-center gap-2">
                                                    <?php if($isSuspended): ?>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            🔒 Tersuspend
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            ✓ Aktif
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="px-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                            <!-- Contact Info -->
                                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                                <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Informasi Kontak
                                                </h5>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                        <span class="font-medium text-gray-700">Email:</span>
                                                        <a href="mailto:<?php echo e($request['email']); ?>" class="text-blue-600 hover:underline"><?php echo e($request['email']); ?></a>
                                                    </div>
                                                    <?php if($request['phone']): ?>
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                            <span class="font-medium text-gray-700">Telpon:</span>
                                                            <a href="tel:<?php echo e($request['phone']); ?>" class="text-blue-600 hover:underline"><?php echo e($request['phone']); ?></a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Company Info -->
                                            <?php if($request['company']): ?>
                                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                                    <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        Detail Perusahaan
                                                    </h5>
                                                    <div class="space-y-2 text-sm">
                                                        <?php if($request['company']->suspend_reason): ?>
                                                            <div>
                                                                <span class="font-medium text-gray-700">Alasan Suspend:</span>
                                                                <p class="text-gray-600 mt-1"><?php echo e($request['company']->suspend_reason); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if($request['company']->subscription_price): ?>
                                                            <div class="flex justify-between">
                                                                <span class="font-medium text-gray-700">Harga Langganan:</span>
                                                                <span class="text-gray-900">Rp <?php echo e(number_format($request['company']->subscription_price, 0, ',', '.')); ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Message -->
                                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                            <h5 class="text-sm font-semibold text-gray-900 mb-2">Pesan dari Pengirim:</h5>
                                            <p class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($request['message']); ?></p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <?php
                                        $isSuspended = $request['company'] && ($request['company']->suspended || $request['company']->subscription_status === 'suspended');
                                        $isUnread = !$request['is_read']; // Notifikasi belum dibaca = request belum di-handle
                                    ?>

                                    <?php if($isSuspended && $isUnread): ?>
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                                            <!-- Reject Button with Modal -->
                                            <button onclick="openRejectModal(<?php echo e($request['company']->id); ?>, '<?php echo e($request['company_name']); ?>')"
                                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>

                                            <!-- Approve Button -->
                                            <form action="<?php echo e(route('super_admin.reactivation.approve', $request['company']->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit"
                                                    onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali akun perusahaan <?php echo e($request['company_name']); ?>?')"
                                                    class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Setujui & Aktifkan Kembali
                                                </button>
                                            </form>
                                        </div>
                                    <?php elseif(!$isSuspended): ?>
                                        <div class="px-6 py-4 bg-green-50 border-t border-green-200">
                                            <p class="text-sm text-green-800 font-medium">✓ Akun sudah aktif kembali</p>
                                        </div>
                                    <?php else: ?>
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                            <p class="text-sm text-gray-600 font-medium">Permintaan sudah ditangani</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Permintaan Reaktivasi</h3>
                <p class="text-sm text-gray-600 mb-4">Perusahaan: <strong id="modalCompanyName"></strong></p>

                <form id="rejectForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label for="reject_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-600">*</span>
                        </label>
                        <textarea id="reject_reason" name="reject_reason" rows="4" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Jelaskan alasan penolakan permintaan reaktivasi..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                    </div>

                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Tolak Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(companyId, companyName) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('modalCompanyName').textContent = companyName;
            document.getElementById('rejectForm').action = `/super-admin/reactivation/reject/${companyId}`;
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('reject_reason').value = '';
        }

        // Close modal on outside click
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/super_admin/reactivation_requests.blade.php ENDPATH**/ ?>