<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="flex flex-1 items-center justify-between sm:hidden">
            <?php if($paginator->onFirstPage()): ?>
                <span class="relative inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500">
                    <?php echo __('pagination.previous'); ?>

                </span>
            <?php else: ?>
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <?php echo __('pagination.previous'); ?>

                </a>
            <?php endif; ?>

            <?php if($paginator->hasMorePages()): ?>
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="relative -ms-px inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <?php echo __('pagination.next'); ?>

                </a>
            <?php else: ?>
                <span class="relative -ms-px inline-flex cursor-default items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500">
                    <?php echo __('pagination.next'); ?>

                </span>
            <?php endif; ?>
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    <?php echo __('Showing'); ?>

                    <span class="font-medium"><?php echo e($paginator->firstItem()); ?></span>
                    <?php echo __('to'); ?>

                    <span class="font-medium"><?php echo e($paginator->lastItem()); ?></span>
                    <?php echo __('of'); ?>

                    <span class="font-medium"><?php echo e($paginator->total()); ?></span>
                    <?php echo __('results'); ?>

                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse gap-1 rounded-md shadow-sm">
                    
                    <?php if($paginator->onFirstPage()): ?>
                        <span aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                            <span class="relative inline-flex cursor-default items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-[#1F8F6A] hover:bg-gray-50" aria-label="<?php echo app('translator')->get('pagination.previous'); ?>">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    
                    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(is_string($element)): ?>
                            <span aria-disabled="true">
                                <span class="relative inline-flex cursor-default items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700"><?php echo e($element); ?></span>
                            </span>
                        <?php endif; ?>

                        
                        <?php if(is_array($element)): ?>
                            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $paginator->currentPage()): ?>
                                    <span aria-current="page">
                                        <span class="relative inline-flex cursor-default items-center border border-[#1F8F6A] bg-[#1F8F6A] px-4 py-2 text-sm font-medium text-white"><?php echo e($page); ?></span>
                                    </span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50" aria-label="<?php echo app('translator')->get('Go to page :page', ['page' => $page]); ?>">
                                        <?php echo e($page); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php if($paginator->hasMorePages()): ?>
                        <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-[#1F8F6A] hover:bg-gray-50" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <span aria-disabled="true" aria-label="<?php echo app('translator')->get('pagination.next'); ?>">
                            <span class="relative inline-flex cursor-default items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </nav>
<?php endif; ?>
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>