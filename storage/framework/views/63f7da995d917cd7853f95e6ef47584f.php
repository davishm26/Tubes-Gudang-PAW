<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e($title ?? config('app.name', 'StockMaster')); ?></title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
        <link rel="alternate icon" href="<?php echo e(asset('favicon.ico')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-[#E9F6F1] via-[#E9F6F1] to-white m-0 p-0">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php
            $isDemoMode = session('is_demo', false) || session('demo_mode', false);
        ?>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header>
                <?php echo e($header); ?>

            </header>
        <?php endif; ?>

        <div class="min-h-screen <?php echo e(isset($header) ? '' : 'pt-16'); ?>">

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>
        </div>

        <!-- Demo Mode Script -->
        <?php if($isDemoMode): ?>
            <script src="<?php echo e(asset('js/demo-mode.js')); ?>"></script>
            <script src="<?php echo e(asset('js/demo-display.js')); ?>"></script>
        <?php endif; ?>

        <!-- Additional Scripts Stack -->
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/layouts/app.blade.php ENDPATH**/ ?>