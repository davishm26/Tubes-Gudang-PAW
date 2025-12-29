<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto p-8 bg-white shadow-lg rounded-lg text-center">
            <h1 class="text-2xl font-bold mb-6">Pembayaran Berhasil!</h1>
            <p class="mb-4">Perusahaan: <?php echo e($company->name); ?></p>
            <p class="mb-4">Admin: <?php echo e($subscription['admin_name']); ?> (<?php echo e($subscription['admin_email']); ?>)</p>
            <p class="mb-4">Password default: password</p>
            <p class="mb-4">Berlaku hingga: <?php echo e($company->subscription_end_date->format('d-m-Y')); ?></p>
            <a href="<?php echo e(route('login')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded">Login Sekarang</a>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\Tubes paw\TUBES-GUDANG-PAW\Tubes-Gudang-PAW\resources\views/subscription/success.blade.php ENDPATH**/ ?>