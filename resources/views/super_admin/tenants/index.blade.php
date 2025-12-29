<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Tenants</h1>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <div class="p-6 border-b border-slate-200">
                    <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 flex flex-col gap-3 md:flex-row">
                            <div class="relative w-full md:max-w-sm">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search ?? request('search') }}"
                                    placeholder="Search tenants by name"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                />
                            </div>
                            <div class="w-full md:w-48">
                                <select
                                    name="status"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                >
                                    <option value="">All status</option>
                                    <option value="active" @selected(($status ?? request('status')) === 'active')>Active</option>
                                    <option value="suspended" @selected(($status ?? request('status')) === 'suspended')>Suspended</option>
                                    <option value="expired" @selected(($status ?? request('status')) === 'expired')>Expired</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                Apply
                            </button>
                            <a href="{{ route('super_admin.tenants.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                @if($companies->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-6 py-3">Tenant Name</th>
                                    <th class="px-6 py-3 text-center">Subscription Status</th>
                                    <th class="px-6 py-3 text-center">Subscription End Date</th>
                                    <th class="px-6 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @foreach($companies as $company)
                                    @php
                                        $deadline = $company->subscription_end_date ?? $company->subscription_expires_at;
                                        $isExpired = $deadline ? \Carbon\Carbon::parse($deadline)->isPast() : false;
                                        $statusLabel = $isExpired ? 'Expired' : ($company->suspended ? 'Suspended' : ($company->subscription_status ?? 'Active'));
                                        $statusColor = $isExpired ? 'bg-rose-50 text-rose-700 ring-rose-100' : ($company->suspended ? 'bg-amber-50 text-amber-700 ring-amber-100' : 'bg-emerald-50 text-emerald-700 ring-emerald-100');
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4 font-medium text-slate-900">{{ $company->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusColor }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-700">
                                            @if($deadline)
                                                {{ \Carbon\Carbon::parse($deadline)->format('d M Y') }}
                                            @else
                                                <span class="text-slate-400">Not set</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-end gap-2 text-sm">
                                                <a href="{{ route('super_admin.tenants.edit', $company) }}" class="inline-flex items-center rounded-md border border-indigo-200 bg-indigo-50 px-3 py-1.5 font-medium text-indigo-700 hover:bg-indigo-100">Edit</a>
                                                <form action="{{ route('super_admin.tenants.send-notification', $company) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-md border border-slate-300 px-3 py-1.5 font-medium text-slate-700 hover:bg-slate-50">Notify</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50 text-sm text-slate-600 md:flex-row md:items-center md:justify-between">
                        <div>
                            Showing {{ $companies->firstItem() }}-{{ $companies->lastItem() }} of {{ $companies->total() }} tenants
                        </div>
                        <div>
                            {{ $companies->onEachSide(1)->links() }}
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center px-6 py-16 text-sm text-slate-500">
                        <div class="text-center">
                            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                                </svg>
                            </div>
                            <p class="font-medium text-slate-700">No tenants available</p>
                            <p class="text-slate-500">Tenants are created via subscriptions. When they appear, you can monitor and manage them here.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
