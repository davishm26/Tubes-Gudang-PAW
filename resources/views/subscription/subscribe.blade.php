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
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('subscription.subscribe') }}">
                @csrf
                <div class="mb-4">
                    <label for="years" class="block text-sm font-medium text-gray-700">Lama Waktu (Tahun)</label>
                    <input type="number" name="years" id="years" min="1" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ old('years', 1) }}">
                </div>
                <div class="mb-4">
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                    <input
                        type="text"
                        name="company_name"
                        id="company_name"
                        required
                        @if(Auth::check() && Auth::user()->company) readonly @endif
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm {{ (Auth::check() && Auth::user()->company) ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        value="{{ old('company_name', Auth::check() && Auth::user()->company ? Auth::user()->company->name : '') }}"
                    >
                </div>
                <div class="mb-4">
                    <label for="admin_name" class="block text-sm font-medium text-gray-700">Nama Admin</label>
                    <input
                        type="text"
                        name="admin_name"
                        id="admin_name"
                        required
                        @if(Auth::check()) readonly @endif
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm {{ Auth::check() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        value="{{ old('admin_name', Auth::check() ? Auth::user()->name : '') }}"
                    >
                </div>
                <div class="mb-4">
                    <label for="admin_email" class="block text-sm font-medium text-gray-700">Email Admin</label>
                    <input
                        type="email"
                        name="admin_email"
                        id="admin_email"
                        required
                        @if(Auth::check()) readonly @endif
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm {{ Auth::check() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        value="{{ old('admin_email', Auth::check() ? Auth::user()->email : '') }}"
                    >
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Lanjutkan ke Pembayaran</button>
            </form>
        </div>
    </div>
</body>
</html>
