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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Audit Log Details')); ?>

            </h2>
            <a href="<?php echo e(route('audit-logs.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to List
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Basic Information</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Timestamp</label>
                                    <p class="text-sm text-gray-900"><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">User</label>
                                    <p class="text-sm text-gray-900"><?php echo e($log->user?->name ?? 'System'); ?></p>
                                    <?php if($log->user): ?>
                                        <p class="text-xs text-gray-500"><?php echo e($log->user->email); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Company</label>
                                    <p class="text-sm text-gray-900"><?php echo e($log->company?->name ?? '-'); ?></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Action</label>
                                    <p>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php echo e($log->action === 'created' ? 'bg-green-100 text-green-800' : ''); ?>

                                            <?php echo e($log->action === 'updated' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                            <?php echo e($log->action === 'deleted' ? 'bg-red-100 text-red-800' : ''); ?>">
                                            <?php echo e(ucfirst($log->action)); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Entity Information</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Entity Type</label>
                                    <p class="text-sm text-gray-900"><?php echo e(class_basename($log->entity_type)); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($log->entity_type); ?></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Entity ID</label>
                                    <p class="text-sm text-gray-900">#<?php echo e($log->entity_id); ?></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">IP Address</label>
                                    <p class="text-sm text-gray-900"><?php echo e($log->ip_address ?? '-'); ?></p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">User Agent</label>
                                    <p class="text-xs text-gray-900 break-all"><?php echo e($log->user_agent ?? '-'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <?php if($log->changes): ?>
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Changes</h3>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <?php $__currentLoopData = $log->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-b-0">
                                        <div class="font-semibold text-gray-700 mb-2"><?php echo e(ucfirst(str_replace('_', ' ', $key))); ?></div>

                                        <?php if(is_array($value) && isset($value['old']) && isset($value['new'])): ?>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-red-50 p-3 rounded border border-red-200">
                                                    <div class="text-xs font-medium text-red-700 mb-1">Old Value</div>
                                                    <div class="text-sm text-red-900">
                                                        <?php if(is_null($value['old'])): ?>
                                                            <span class="italic text-gray-400">null</span>
                                                        <?php else: ?>
                                                            <?php echo e(is_array($value['old']) ? json_encode($value['old']) : $value['old']); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="bg-green-50 p-3 rounded border border-green-200">
                                                    <div class="text-xs font-medium text-green-700 mb-1">New Value</div>
                                                    <div class="text-sm text-green-900">
                                                        <?php if(is_null($value['new'])): ?>
                                                            <span class="italic text-gray-400">null</span>
                                                        <?php else: ?>
                                                            <?php echo e(is_array($value['new']) ? json_encode($value['new']) : $value['new']); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                                <pre class="text-xs text-blue-900 overflow-auto"><?php echo e(is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value); ?></pre>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Changes</h3>
                            <p class="text-sm text-gray-500 italic">No change details recorded.</p>
                        </div>
                    <?php endif; ?>

                    
                    <div class="mt-8 pt-6 border-t">
                        <div class="grid grid-cols-2 gap-4 text-xs text-gray-500">
                            <div>
                                <span class="font-medium">Log ID:</span> <?php echo e($log->id); ?>

                            </div>
                            <div>
                                <span class="font-medium">Company ID:</span> <?php echo e($log->company_id ?? '-'); ?>

                            </div>
                        </div>
                    </div>
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
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/audit_logs/show.blade.php ENDPATH**/ ?>