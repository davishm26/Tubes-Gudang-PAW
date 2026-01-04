<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e($title ?? config('app.name', 'StockMaster')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-emerald-50 via-emerald-50 to-white">
        <div class="min-h-screen">
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Demo Mode Banner -->
            <?php if(session('demo_mode') === 'true'): ?>
                <div class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 text-white shadow-lg">
                    <div class="max-w-7xl mx-auto py-3 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between flex-wrap">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-bold text-sm">Mode Demo - <?php echo e(ucfirst(session('demo_role', 'Staff'))); ?></p>
                                    <p class="text-xs opacity-90">Semua data hanya disimpan di browser Anda. Tidak ada perubahan permanen.</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                                <a href="<?php echo e(route('demo.exit')); ?>" class="bg-white text-orange-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors duration-200 shadow-md">
                                    Keluar Demo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>
        </div>

        <!-- Demo Mode Script -->
        <?php if(session('demo_mode') === 'true'): ?>
            <script src="<?php echo e(asset('js/demo-mode.js')); ?>"></script>
            <script src="<?php echo e(asset('js/demo-display.js')); ?>"></script>
        <?php endif; ?>

        <!-- Additional Scripts Stack -->
        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>



<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/layouts/app.blade.php ENDPATH**/ ?>