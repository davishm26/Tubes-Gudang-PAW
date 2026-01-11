<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Langganan StockMaster</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto p-8 bg-white shadow-lg rounded-lg">
            <h1 class="text-2xl font-bold text-center mb-6">Langganan StockMaster</h1>
            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('subscription.subscribe')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="years" class="block text-sm font-medium text-gray-700">Lama Waktu (Tahun)</label>
                    <input type="number" name="years" id="years" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="<?php echo e(old('years', 1)); ?>">
                </div>
                <div class="mb-4">
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <input
                        type="text"
                        name="company_name"
                        id="company_name"
                        required
                        <?php if(Auth::check() && Auth::user()->company): ?> readonly <?php endif; ?>
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm <?php echo e((Auth::check() && Auth::user()->company) ? 'bg-gray-100 cursor-not-allowed' : ''); ?>"
                        value="<?php echo e(old('company_name', Auth::check() && Auth::user()->company ? Auth::user()->company->name : '')); ?>"
                    >
                </div>
                <div class="mb-4">
                    <label for="admin_name" class="block text-sm font-medium text-gray-700">Nama Admin</label>
                    <input
                        type="text"
                        name="admin_name"
                        id="admin_name"
                        required
                        <?php if(Auth::check()): ?> readonly <?php endif; ?>
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm <?php echo e(Auth::check() ? 'bg-gray-100 cursor-not-allowed' : ''); ?>"
                        value="<?php echo e(old('admin_name', Auth::check() ? Auth::user()->name : '')); ?>"
                    >
                </div>
                <div class="mb-4">
                    <label for="admin_email" class="block text-sm font-medium text-gray-700">Email Admin</label>
                    <input
                        type="email"
                        name="admin_email"
                        id="admin_email"
                        required
                        <?php if(Auth::check()): ?> readonly <?php endif; ?>
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm <?php echo e(Auth::check() ? 'bg-gray-100 cursor-not-allowed' : ''); ?>"
                        value="<?php echo e(old('admin_email', Auth::check() ? Auth::user()->email : '')); ?>"
                    >
                </div>
                <button type="submit" class="w-full bg-[#1F8F6A] text-white py-2 rounded-lg hover:bg-[#166B50] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">Lanjutkan ke Pembayaran</button>
            </form>
        </div>
    </div>
</body>
</html>






<?php /**PATH D:\Semester 3\PAW\TUBES\tubes-gudang\resources\views/subscription/subscribe.blade.php ENDPATH**/ ?>