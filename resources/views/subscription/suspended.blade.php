<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Suspended</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-2xl font-bold text-gray-900 mb-4">Akun Suspended</h1>

                <!-- Message -->
                <p class="text-gray-600 mb-6">
                    Akun perusahaan Anda telah di-suspend oleh administrator sistem.
                    Anda tidak dapat mengakses aplikasi saat ini.
                </p>

                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                    <h3 class="font-semibold text-blue-900 mb-2">Apa yang harus dilakukan?</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Hubungi administrator sistem</li>
                        <li>• Periksa status pembayaran langganan Anda</li>
                        <li>• Pastikan tidak ada pelanggaran ketentuan layanan</li>
                    </ul>
                </div>

                <!-- Action Button -->
                <a href="{{ route('subscription.landing') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-200">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>
