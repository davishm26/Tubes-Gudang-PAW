<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto p-8 bg-white shadow-lg rounded-lg text-center">
            <h1 class="text-2xl font-bold mb-6">Pembayaran</h1>
            <p class="mb-4">Harga: Rp <?php echo e(number_format($subscription['price'], 0, ',', '.')); ?></p>
            <p class="mb-4">Scan QR Code berikut untuk membayar:</p>
            <img src="<?php echo e($qrCodeDataUri); ?>" alt="QR Code" class="mx-auto mb-4">
            <p class="text-sm text-gray-600 mb-4">Setelah scan, klik tombol di bawah untuk konfirmasi pembayaran.</p>
            <form method="POST" action="<?php echo e(route('subscription.pay', ['token' => $subscription['token']])); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Konfirmasi Pembayaran</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\X1 Yoga\SEMESTER 3\Tubes paw\TUBES-GUDANG-PAW\Tubes-Gudang-PAW\resources\views/subscription/payment.blade.php ENDPATH**/ ?>