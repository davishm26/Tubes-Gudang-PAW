<x-app-layout>
    <div class="py-10">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-6" x-data="{ status: '{{ old('tenant_status', $company->subscription_status ?? 'active') }}' }">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Edit: {{ $company->name }}</h2>
                    <p class="text-sm text-slate-500">Update tenant profile and access controls.</p>
                </div>

                <form method="POST" action="{{ route('super_admin.tenants.update', $company) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700" for="name">Tenant Name</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $company->name) }}"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        />
                    </div>

                    <div class="space-y-3">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700" for="tenant_status">Tenant Status</label>
                            <select
                                id="tenant_status"
                                name="tenant_status"
                                x-model="status"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            <p class="text-xs text-slate-500">Controls tenant access only. Subscription/billing is unchanged.</p>
                        </div>

                        <div x-show="status === 'suspended'" x-cloak class="space-y-2">
                            <label class="text-sm font-medium text-slate-700" for="suspend_reason">Suspend Reason</label>
                            <textarea
                                id="suspend_reason"
                                name="suspend_reason"
                                rows="3"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                                placeholder="Optional: note why this tenant is suspended"
                            >{{ old('suspend_reason', $company->meta['suspend_reason'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('super_admin.tenants.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
