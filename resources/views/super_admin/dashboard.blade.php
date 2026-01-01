<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dasbor Super Admin') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Statistik Global</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Tenant:</strong>
                        <div class="text-2xl">{{ $totalTenants }}</div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Pengguna:</strong>
                        <div class="text-2xl">{{ $allUsers->count() }}</div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Pendapatan Langganan:</strong>
                        <div class="text-2xl">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold mb-4">Tenant</h4>
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden" style="table-layout: fixed;">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Nama</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Langganan</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 w-1/3">Pengguna</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($tenants as $t)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 w-1/3">{{ $t->name }}</td>
                                    <td class="px-6 py-4 text-center text-sm w-1/3">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $t->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $t->subscription_status === 'active' ? 'Aktif' : 'Tersuspend' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 font-medium w-1/3">{{ $t->users->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
