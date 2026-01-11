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
     <?php $__env->slot('title', null, []); ?> Manajemen Pengguna - StockMaster <?php $__env->endSlot(); ?>
    <?php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
        $demoRole = session('demo_role', null);

        // Tentukan apakah user adalah admin
        if ($isDemo) {
            $isAdmin = ($demoRole === 'admin');
        } else {
            $isAdmin = (Auth::user() && Auth::user()->role === 'admin');
        }
    ?>

     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight"><?php echo e(__('Manajemen User')); ?></h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 rounded bg-[#E9F6F1] text-[#1F8F6A] border border-[#E5E7EB]">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('info')): ?>
                    <div class="mb-4 p-3 rounded bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]">
                        <?php echo e(session('info')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-rose-200">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Pengguna</h3>
                    <?php if($isAdmin): ?>
                    <a href="<?php echo e(route('users.create')); ?>" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                        + Tambah Pengguna
                    </a>
                    <?php endif; ?>
                </div>

                
                <form method="GET" action="<?php echo e(route('users.index')); ?>" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari user (nama, email, role)..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-6 py-2 rounded-r-xl font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E5E7EB]">
                        <thead class="bg-[#E9F6F1]">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Nama</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Email</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Role</th>
                                <th class="py-3 px-4 text-center text-xs font-semibold text-[#1F8F6A] uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-3 px-4 text-slate-900 font-medium"><?php echo e($user->name ?? '-'); ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?php echo e($user->email ?? '-'); ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?php echo e($user->role === 'admin' ? 'bg-[#F0FAF7] text-[#1F8F6A]' : 'bg-slate-100 text-slate-700'); ?>">
                                            <?php echo e($user->role === 'staf' ? 'Staf' : ($user->role ?? '-')); ?>

                                        </span>
                                    </td>
                                    <td class="py-3 px-4 flex justify-center gap-1">
                                        <?php if($isAdmin): ?>
                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-50 text-red-700 hover:bg-red-100 transition">Hapus</button>
                                        </form>
                                        <?php else: ?>
                                        <span class="text-slate-400 text-sm">Read Only</span>
                                        <?php endif; ?>
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