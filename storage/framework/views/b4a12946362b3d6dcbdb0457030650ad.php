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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Keuangan</h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="<?php echo e(route('super_admin.financial-report')); ?>" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="<?php echo e($startDate); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="<?php echo e($endDate); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="p-4 bg-emerald-50 rounded">
                        <strong>Pemasukan Langganan:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($subscriptionRevenue, 0, ',', '.')); ?></div>
                    </div>

                    <div class="p-4 bg-green-50 rounded">
                        <strong>Pemasukan Operasional:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($operationalIncome, 0, ',', '.')); ?></div>
                    </div>

                    <div class="p-4 bg-red-50 rounded">
                        <strong>Total Pengeluaran:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="p-4 bg-blue-50 rounded">
                        <strong>Total Pemasukan:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></div>
                    </div>

                    <div class="p-4 bg-indigo-50 rounded">
                        <strong>Profit:</strong>
                        <div class="text-2xl">Rp <?php echo e(number_format($profit, 0, ',', '.')); ?></div>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('super_admin.financial-report.download')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e($startDate); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e($endDate); ?>">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Download PDF</button>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/super_admin/financial_report.blade.php ENDPATH**/ ?>