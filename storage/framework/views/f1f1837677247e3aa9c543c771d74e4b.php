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
     <?php $__env->slot('title', null, []); ?> Edit Kategori - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            <?php echo e(__('Edit Kategori: ') . $category->name); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-[#E5E7EB]">

                    
                    <?php if(session('success')): ?>
                        <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F8F6A] px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                        </div>
                    <?php endif; ?>
                    

                    
                    <form method="POST" action="<?php echo e(route('categories.update', $category->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?> 

                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Kategori</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="<?php echo e(old('name', $category->name)); ?>"
                                   class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A] sm:text-sm"
                                   required>

                            
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="flex items-center justify-end mt-6">
                            <a href="<?php echo e(route('categories.index')); ?>" class="text-sm text-slate-600 hover:text-slate-900 mr-4 font-medium">
                                Batal
                            </a>

                            <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150">
                                Perbarui Kategori
                            </button>
                        </div>
                    </form>

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






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/categories/edit.blade.php ENDPATH**/ ?>