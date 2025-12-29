<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tenants</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('super_admin.tenants.create') }}" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Tenant</a>

                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Subscription</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Subscription End Date</th>
                            <th class="border border-gray-200 px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $c)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="border border-gray-200 px-6 py-4 text-sm text-gray-900">{{ $c->name }}</td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $c->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($c->subscription_status) }}
                                    </span>
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm text-gray-900">
                                    @php
                                        $deadline = $c->subscription_end_date ?? $c->subscription_expires_at;
                                    @endphp
                                    @if($deadline)
                                        {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y') }}
                                    @else
                                        No deadline set
                                    @endif
                                </td>
                                <td class="border border-gray-200 px-6 py-4 text-center text-sm space-x-2">
                                    <a href="{{ route('super_admin.tenants.edit', $c) }}" class="inline text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                                    <form action="{{ route('super_admin.tenants.send-notification', $c) }}" method="POST" style="display:inline">@csrf<button type="submit" class="inline text-blue-600 hover:text-blue-800 font-medium">Notify</button></form>
                                    @if($c->subscription_status !== 'suspended')
                                        <form action="{{ route('super_admin.tenants.suspend', $c) }}" method="POST" style="display:inline">@csrf<button type="submit" class="inline text-red-600 hover:text-red-800 font-medium">Suspend</button></form>
                                    @endif
                                    <form action="{{ route('super_admin.tenants.destroy', $c) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure?')">@csrf @method('DELETE')<button type="submit" class="inline text-red-600 hover:text-red-800 font-medium">Delete</button></form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
