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
     <?php $__env->slot('title', null, []); ?> Manajemen Produk - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight"><?php echo e(__('Manajemen Produk')); ?></h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <?php
        // Cek mode demo
        $isDemo = session('is_demo', false);
        $demoRole = session('demo_role', null);

        // Tentukan apakah user adalah admin
        if ($isDemo) {
            $isAdmin = ($demoRole === 'admin');
        } else {
            $isAdmin = (Auth::user()->role === 'admin');
        }
    ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <?php if(session('success')): ?>
                    <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F2937] px-4 py-3 rounded-lg relative mb-4">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="mb-4 p-3 rounded bg-rose-50 text-rose-800 border border-[#E5E7EB]">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900">Daftar Produk</h3>
                    <?php if($isAdmin): ?>
                        <a href="<?php echo e(route('products.create')); ?>" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-4 py-2 rounded-xl text-sm font-semibold transition">
                            + Tambah Produk Baru
                        </a>
                    <?php endif; ?>
                </div>

                
                <form method="GET" action="<?php echo e(route('products.index')); ?>" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari produk (nama, SKU, kategori, pemasok)..." class="flex-1 px-4 py-2 border border-[#E5E7EB] rounded-l-xl focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-6 py-2 rounded-r-xl font-semibold transition">
                            Cari
                        </button>
                    </div>
                </form>

                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E5E7EB]">
                        <thead class="bg-[#E9F6F1]">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Gambar</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Nama & SKU</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Kategori</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Pemasok</th>
                                <th class="py-3 px-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase">Stok</th>
                                <?php if($isAdmin): ?>
                                    <th class="py-3 px-4 text-center text-xs font-semibold text-[#1F8F6A] uppercase tracking-wider">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-[#E9F6F1]/30 transition">
                                    <td class="py-3 px-4">
                                        <?php if($product->image): ?>
                                            <?php
                                                // Jika URL absolut (http/https), gunakan langsung
                                                $imageUrl = (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://'))
                                                    ? $product->image
                                                    : Storage::url($product->image);
                                            ?>
                                            <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product->name); ?>" class="w-12 h-12 object-cover rounded border border-gray-200">
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">Tidak Ada Gambar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-sm font-medium text-slate-900"><?php echo e($product->name); ?></div>
                                        <div class="text-xs text-slate-600">SKU: <?php echo e($product->sku ?? $product->code ?? '-'); ?></div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-600"><?php echo e($product->category->name ?? '-'); ?></td>
                                    <td class="py-3 px-4 text-slate-600"><?php echo e($product->supplier->name ?? '-'); ?></td>
                                    <td class="py-3 px-4 font-semibold
                                        <?php echo e($product->stock < 10 ? 'text-rose-600' : 'text-[#1F8F6A]'); ?>">
                                        <?php echo e(number_format($product->stock)); ?>

                                    </td>

                                    <?php if($isAdmin): ?>
                                        <td class="py-3 px-4 flex justify-center gap-1">
                                            <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-blue-50 text-blue-700 hover:bg-blue-100 transition">Edit</a>

                                            
                                            <?php if(!$isDemo || $demoRole !== 'staff'): ?>
                                                <form action="<?php echo e(route('products.destroy', $product->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded bg-red-50 text-red-700 hover:bg-red-100 transition">Hapus</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">Belum ada data produk yang dicatat.</td>
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






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/products/index.blade.php ENDPATH**/ ?>