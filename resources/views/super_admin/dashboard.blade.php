<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Super Admin Dashboard') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Global Statistics</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Tenants:</strong>
                        <div class="text-2xl">{{ $totalTenants }}</div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Users:</strong>
                        <div class="text-2xl">{{ $allUsers->count() }}</div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded">
                        <strong>Total Revenue (dummy):</strong>
                        <div class="text-2xl">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold">Tenants</h4>
                    <table class="min-w-full mt-2 border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2">Subscription</th>
                                <th class="p-2">Suspended</th>
                                <th class="p-2">Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $t)
                                <tr class="border-t">
                                    <td class="p-2">{{ $t->name }}</td>
                                    <td class="p-2">{{ $t->subscription_status }}</td>
                                    <td class="p-2 text-center">{{ $t->suspended ? 'Yes' : 'No' }}</td>
                                    <td class="p-2">{{ $t->users->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
