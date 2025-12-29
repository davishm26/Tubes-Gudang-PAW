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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Manajemen Pemasok')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                
                <?php ($currentUser = $demoUser ?? Auth::user()); ?>
                <?php if(!session('demo_mode') && $currentUser && $currentUser->role === 'admin'): ?>
                    <a href="<?php echo e(route('suppliers.create')); ?>"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded mb-4 inline-block shadow-md">
                        + Tambah Pemasok
                    </a>
                <?php endif; ?>

                
                <?php if(session('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                
                <form method="GET" action="<?php echo e(route('suppliers.index')); ?>" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari pemasok atau kontak..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                            Cari
                        </button>
                    </div>
                </form>

                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pemasok</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="py-4 px-6 whitespace-nowrap"><?php echo e($supplier->id); ?></td>
                                    <td class="py-4 px-6 whitespace-nowrap font-medium text-gray-900"><?php echo e($supplier->name); ?></td>
                                    <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-500"><?php echo e($supplier->contact ?? '-'); ?></td>

                                    <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                        <a href="<?php echo e(route('suppliers.edit', $supplier->id)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                        <form action="<?php echo e(route('suppliers.destroy', $supplier->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus pemasok ini?')" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">Belum ada data pemasok yang dicatat.</td>
                                </tr>
                            <?php endif; ?>
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
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\Tubes paw\TUBES-GUDANG-PAW\Tubes-Gudang-PAW\resources\views/suppliers/index.blade.php ENDPATH**/ ?>