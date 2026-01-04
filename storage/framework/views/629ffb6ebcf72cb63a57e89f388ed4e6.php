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
     <?php $__env->slot('title', null, []); ?> Manajemen Kategori - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            <?php echo e(__('Manajemen Kategori')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-300">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('info')): ?>
                    <div class="mb-4 p-3 rounded-lg bg-sky-50 text-sky-800 border border-sky-300">
                        <?php echo e(session('info')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-800 border border-rose-300">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <a href="<?php echo e(route('categories.create')); ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg mb-4 inline-block font-semibold transition">Tambah Kategori</a>

                
                <form method="GET" action="<?php echo e(route('categories.index')); ?>" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari kategori..." class="flex-1 px-4 py-2 border border-emerald-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-r-lg font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                <table class="w-full text-left border-collapse">
                    <thead class="bg-emerald-50">
                        <tr>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">No.</th>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">Nama Kategori</th>
                            <th class="py-3 px-4 border-b-2 border-emerald-300 font-semibold text-emerald-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-emerald-50/30 transition">
                                <td class="py-3 px-4 text-slate-900"><?php echo e($loop->iteration); ?></td>
                                <td class="py-3 px-4 text-slate-900 font-medium"><?php echo e($category->name); ?></td>
                                <td class="py-3 px-4">
                                    
                                    <a href="<?php echo e(route('categories.edit', $category->id)); ?>" class="text-emerald-600 hover:text-emerald-900 font-semibold mr-4">
                                        Edit
                                    </a>

                                    
                                    <form action="<?php echo e(route('categories.destroy', $category->id)); ?>" method="POST" class="inline">
                                        
                                        <?php echo csrf_field(); ?>

                                        
                                        <?php echo method_field('DELETE'); ?>

                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori: <?php echo e($category->name); ?>?')"
                                            class="text-rose-600 hover:text-rose-900 font-semibold">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
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
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\tubespawpalingbarubanget\Tubes-Gudang-PAW\resources\views/categories/index.blade.php ENDPATH**/ ?>