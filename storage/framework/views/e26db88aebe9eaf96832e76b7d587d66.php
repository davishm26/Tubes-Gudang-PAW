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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight"><?php echo e(__('Manajemen User')); ?></h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('info')): ?>
                    <div class="mb-4 p-3 rounded bg-blue-50 text-blue-800 border border-blue-200">
                        <?php echo e(session('info')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium">Daftar Pengguna</h3>
                    <a href="<?php echo e(route('users.create')); ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-sm">
                        + Tambah Pengguna
                    </a>
                </div>

                
                <form method="GET" action="<?php echo e(route('users.index')); ?>" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari user (nama, email, role)..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="py-2 px-4"><?php echo e($user->name); ?></td>
                                    <td class="py-2 px-4"><?php echo e($user->email); ?></td>
                                    <td class="py-2 px-4"><?php echo e($user->role === 'staf' ? 'Staf' : ($user->role ?? '-')); ?></td>
                                    <td class="py-2 px-4 text-right">
                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td class="py-4 px-4" colspan="4">Belum ada pengguna.</td>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/users/index.blade.php ENDPATH**/ ?>