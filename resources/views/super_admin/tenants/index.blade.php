<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tenants</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('super_admin.tenants.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-3 py-2 rounded">Create Tenant</a>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3">Subscription</th>
                            <th class="px-6 py-3">Sisa Waktu</th>
                            <th class="px-6 py-3">Suspended</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($companies as $c)
                            <tr>
                                <td class="px-6 py-4">{{ $c->name }}</td>
                                <td class="px-6 py-4">{{ $c->subscription_status }}</td>
                                <td class="px-6 py-4">
                                    @if($c->subscription_expires_at)
                                        @php
                                            $daysLeft = now()->diffInDays($c->subscription_expires_at, false);
                                        @endphp
                                        @if($daysLeft > 0)
                                            {{ $daysLeft }} hari
                                        @elseif($daysLeft == 0)
                                            Hari ini
                                        @else
                                            Kadaluarsa {{ abs($daysLeft) }} hari yang lalu
                                        @endif
                                    @else
                                        Tidak ada batas
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $c->suspended ? 'Yes' : 'No' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('super_admin.tenants.edit', $c) }}" class="text-indigo-600 mr-2">Edit</a>
                                    <form action="{{ route('super_admin.tenants.send-notification', $c) }}" method="POST" style="display:inline">@csrf<button class="text-blue-600 ml-2">Kirim Notifikasi</button></form>
                                    @if(!$c->suspended)
                                        <form action="{{ route('super_admin.tenants.suspend', $c) }}" method="POST" style="display:inline">@csrf<button class="text-red-600 ml-2">Suspend</button></form>
                                    @else
                                        <form action="{{ route('super_admin.tenants.unsuspend', $c) }}" method="POST" style="display:inline">@csrf<button class="text-green-600 ml-2">Unsuspend</button></form>
                                    @endif
                                    <form action="{{ route('super_admin.tenants.destroy', $c) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete tenant?')">@csrf @method('DELETE')<button class="text-red-800 ml-2">Delete</button></form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
