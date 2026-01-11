<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title' => 'Beranda',
    'subtitle' => null,
    'icon' => true,
    'variant' => 'default', // default | minimal
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title' => 'Beranda',
    'subtitle' => null,
    'icon' => true,
    'variant' => 'default', // default | minimal
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($variant === 'minimal'): ?>
    <div class="flex items-center justify-between py-2">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                <?php echo e($title); ?>

            </h2>
            <div class="h-1 w-16 bg-[#1F8F6A] rounded-full mt-1"></div>
            <?php if($subtitle): ?>
                <p class="text-sm text-gray-500 mt-2"><?php echo e($subtitle); ?></p>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-2">
            <?php echo e($actions ?? ''); ?>

        </div>
    </div>
<?php else: ?>
    <div class="flex items-center justify-between py-2">
        <div class="flex items-center gap-4">
            <?php if($icon): ?>
                <div class="h-10 w-10 rounded-full bg-[#1F8F6A] flex items-center justify-center text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9.75A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 .75.75V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.75Z"/>
                        <path d="M21 8H3l8.485-5.657a1 1 0 0 1 1.03 0L21 8Z"/>
                    </svg>
                </div>
            <?php endif; ?>
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight bg-clip-text text-transparent bg-gradient-to-r from-[#1F8F6A] to-[#14B8A6]">
                    <?php echo e($title); ?>

                </h2>
                <?php if($subtitle): ?>
                    <p class="text-sm text-gray-500"><?php echo e($subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <?php echo e($actions ?? ''); ?>

        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/components/page-heading.blade.php ENDPATH**/ ?>