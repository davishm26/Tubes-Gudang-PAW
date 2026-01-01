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
            <p class="mb-4">Perusahaan: {{ $company->name }}</p>

            @if(!empty($subscription['renew_company_id']))
                <p class="mb-4">Perpanjangan langganan berhasil.</p>
            @else
                <p class="mb-4">Admin: {{ $subscription['admin_name'] }} ({{ $subscription['admin_email'] }})</p>
                <p class="mb-4">Password default: password</p>
            @endif

            <p class="mb-4">Berlaku hingga: {{ $company->subscription_end_date->format('d-m-Y') }}</p>
            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Login Sekarang</a>
        </div>
    </div>
</body>
</html>
