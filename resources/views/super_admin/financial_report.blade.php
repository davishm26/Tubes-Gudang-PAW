<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Keuangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('super_admin.financial-report') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
                        </div>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="p-4 bg-emerald-50 rounded">
                        <strong>Pemasukan Langganan:</strong>
                        <div class="text-2xl">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</div>
                    </div>

                    <div class="p-4 bg-green-50 rounded">
                        <strong>Pemasukan Operasional:</strong>
                        <div class="text-2xl">Rp {{ number_format($operationalIncome, 0, ',', '.') }}</div>
                    </div>

                    <div class="p-4 bg-red-50 rounded">
                        <strong>Total Pengeluaran:</strong>
                        <div class="text-2xl">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="p-4 bg-blue-50 rounded">
                        <strong>Total Pemasukan:</strong>
                        <div class="text-2xl">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    </div>

                    <div class="p-4 bg-indigo-50 rounded">
                        <strong>Profit:</strong>
                        <div class="text-2xl">Rp {{ number_format($profit, 0, ',', '.') }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('super_admin.financial-report.download') }}">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Download PDF</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
