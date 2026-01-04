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
     <?php $__env->slot('title', null, []); ?> Riwayat Audit - StockMaster <?php $__env->endSlot(); ?>
    <?php
        // Check demo mode
        $isDemo = session('is_demo', false) || session('demo_mode', false);

        // Helper function untuk ambil nama entity (hanya untuk real mode)
        function getEntityName($log) {
            $entityType = class_basename($log->entity_type);
            $entityId = $log->entity_id;

            try {
                // Map class ke model
                $modelClass = 'App\\Models\\' . $entityType;
                if (class_exists($modelClass)) {
                    $entity = $modelClass::find($entityId);
                    if ($entity) {
                        // Return nama/identitas entity
                        if (isset($entity->name)) {
                            return $entity->name;
                        } elseif (isset($entity->title)) {
                            return $entity->title;
                        } elseif ($entityType === 'User') {
                            return $entity->name . ' (' . $entity->email . ')';
                        }
                    } else {
                        // Entity sudah dihapus
                        return '[Deleted ' . $entityType . ']';
                    }
                }
            } catch (\Exception $e) {
                // Silent fail
            }

            return '[Unknown]';
        }
    ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Riwayat Audit')); ?>

            </h2>
            <?php if($isDemo): ?>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    Demo Mode - Data Dummy
                </span>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <?php if($isDemo): ?>
                        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Demo Mode:</strong> Anda sedang melihat audit logs demo dengan 8 data contoh. Klik pada setiap baris untuk melihat detail lengkap.
                            </p>
                        </div>
                    <?php endif; ?>

                    
                    <?php if(!$isDemo): ?>
                    <form method="GET" action="<?php echo e(route('audit-logs.index')); ?>" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                            
                            <div>
                                <label for="entity_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Entitas</label>
                                <select name="entity_type" id="entity_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Tipe</option>
                                    <?php $__currentLoopData = $entityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>" <?php echo e(request('entity_type') == $type ? 'selected' : ''); ?>>
                                            <?php echo e($type); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                                <select name="action" id="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Aksi</option>
                                    <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst($action)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pengguna</label>
                                <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Pengguna</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <?php if(!$isDemo && Auth::user() && Auth::user()->role === 'super_admin' && count($companies) > 0): ?>
                                <div>
                                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                                    <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Semua Perusahaan</option>
                                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                                                <?php echo e($company->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="date_from" id="date_from" value="<?php echo e(request('date_from')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" name="date_to" id="date_to" value="<?php echo e(request('date_to')); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" placeholder="Cari..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Terapkan
                            </button>
                            <a href="<?php echo e(route('audit-logs.index')); ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Atur Ulang
                            </a>
                        </div>
                    </form>
                    <?php endif; ?>

                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengguna</th>
                                    <?php if(!$isDemo && Auth::user() && Auth::user()->role === 'super_admin'): ?>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                    <?php endif; ?>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perubahan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat IP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if($isDemo): ?>
                                                <div class="font-medium"><?php echo e($log['created_at'] ?? $log['timestamp']); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($log['created_at'] ?? $log['timestamp'])->diffForHumans()); ?></div>
                                            <?php else: ?>
                                                <div class="font-medium"><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e($log->created_at->diffForHumans()); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php if($isDemo): ?>
                                                <?php echo e($log['user_name'] ?? '-'); ?>

                                            <?php else: ?>
                                                <?php echo e($log->user?->name ?? '-'); ?>

                                            <?php endif; ?>
                                        </td>
                                        <?php if(!$isDemo && Auth::user() && Auth::user()->role === 'super_admin'): ?>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?php echo e($log->company?->name ?? '-'); ?>

                                            </td>
                                        <?php endif; ?>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?php if($isDemo): ?>
                                                <div class="font-medium"><?php echo e($log['entity'] ?? '[Unknown]'); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e($log['entity_name'] ?? '[Unknown]'); ?> <span class="text-gray-400">#<?php echo e($log['entity_id'] ?? '-'); ?></span></div>
                                            <?php else: ?>
                                                <div class="font-medium"><?php echo e(class_basename($log->entity_type)); ?></div>
                                                <div class="text-xs text-gray-500"><?php echo e($log->entity_name ?? '[Unknown]'); ?> <span class="text-gray-400">#<?php echo e($log->entity_id); ?></span></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php echo e(($log['action'] ?? $log->action ?? null) === 'created' ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e(($log['action'] ?? $log->action ?? null) === 'updated' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                                <?php echo e(($log['action'] ?? $log->action ?? null) === 'deleted' ? 'bg-red-100 text-red-800' : ''); ?>

                                                <?php echo e(($log['action'] ?? $log->action ?? null) === 'viewed' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                                <?php echo e(ucfirst($log['action'] ?? $log->action ?? 'Unknown')); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?php if($isDemo): ?>
                                                <?php if($log['old_values'] || $log['new_values']): ?>
                                                    <button onclick="showChanges(<?php echo e(json_encode(['old' => $log['old_values'], 'new' => $log['new_values']])); ?>)" class="text-indigo-600 hover:text-indigo-900">
                                                        Lihat Perubahan
                                                    </button>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?php if($log->changes): ?>
                                                    <button onclick="showChanges(<?php echo e(json_encode($log->changes)); ?>)" class="text-indigo-600 hover:text-indigo-900">
                                                        Lihat Perubahan
                                                    </button>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php if($isDemo): ?>
                                                -
                                            <?php else: ?>
                                                <?php echo e($log->ip_address ?? '-'); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <?php if($isDemo): ?>
                                                <a href="<?php echo e(route('audit-logs.show', $log['id'])); ?>" class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('audit-logs.show', $log->id)); ?>" class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="<?php echo e($isDemo || !Auth::user() || Auth::user()->role !== 'super_admin' ? '7' : '8'); ?>" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada riwayat audit.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if(!$isDemo): ?>
                    <div class="mt-4">
                        <?php echo e($logs->links()); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div id="changesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Perubahan</h3>
                <button onclick="closeChangesModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="changesContent" class="mt-2">
                <!-- Changes will be inserted here -->
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeChangesModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function showChanges(changes) {
            const modal = document.getElementById('changesModal');
            const content = document.getElementById('changesContent');

            let html = '<div class="space-y-3">';

            for (const [key, value] of Object.entries(changes)) {
                html += '<div class="border-b pb-3">';
                html += `<div class="font-semibold text-gray-700">${key}</div>`;

                if (value.old !== undefined && value.new !== undefined) {
                    html += '<div class="grid grid-cols-2 gap-4 mt-2">';
                    html += '<div><span class="text-xs text-gray-500">Lama:</span><div class="text-sm text-red-600">' + (value.old ?? 'null') + '</div></div>';
                    html += '<div><span class="text-xs text-gray-500">Baru:</span><div class="text-sm text-green-600">' + (value.new ?? 'null') + '</div></div>';
                    html += '</div>';
                } else {
                    html += '<pre class="text-sm bg-gray-50 p-2 rounded mt-2 overflow-auto">' + JSON.stringify(value, null, 2) + '</pre>';
                }

                html += '</div>';
            }

            html += '</div>';
            content.innerHTML = html;
            modal.classList.remove('hidden');
        }

        function closeChangesModal() {
            document.getElementById('changesModal').classList.add('hidden');
        }
    </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/audit_logs/index.blade.php ENDPATH**/ ?>