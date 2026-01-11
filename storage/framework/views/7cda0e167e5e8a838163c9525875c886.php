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
     <?php $__env->slot('title', null, []); ?> Tambah Pengguna - StockMaster <?php $__env->endSlot(); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight"><?php echo e(__('Tambah Pengguna')); ?></h2>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">

                <form method="POST" action="<?php echo e(route('users.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Nama</label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="mt-1 block w-full border border-[#E5E7EB] rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="mt-1 block w-full border border-[#E5E7EB] rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Password</label>
                            <input type="password" name="password" class="mt-1 block w-full border border-[#E5E7EB] rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full border border-[#E5E7EB] rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Role</label>
                            <select name="role" class="mt-1 block w-full border border-[#E5E7EB] rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($r); ?>" <?php echo e(old('role')===$r ? 'selected' : ''); ?>><?php echo e($r === 'staf' ? 'Staf' : ucfirst($r)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="flex justify-end gap-3 mt-4">
                            <a href="<?php echo e(route('users.index')); ?>" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</a>
                            <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white px-6 py-2 rounded-xl font-semibold transition">Simpan</button>
                        </div>
                    </div>
                </form>

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






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/users/create.blade.php ENDPATH**/ ?>