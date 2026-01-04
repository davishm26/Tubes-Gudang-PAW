<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Card Container -->
            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden" x-data="{ status: '{{ old('tenant_status', $company->subscription_status ?? 'active') }}' }">

                <!-- Header Section -->
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-6 border-b border-slate-700">
                    <h1 class="text-2xl font-bold text-white mb-1">Edit Tenant Profile</h1>
                    <p class="text-slate-300 text-sm">Update {{ $company->name }} â€” access controls and tenant information</p>
                </div>

                <!-- Form Section -->
                <form method="POST" action="{{ route('super_admin.tenants.update', $company) }}" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Tenant Information Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-700 font-semibold text-sm">1</span>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">Tenant Information</h2>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-slate-800" for="name">
                                Tenant Name
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $company->name) }}"
                                required
                                maxlength="150"
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none"
                                placeholder="Enter tenant company name"
                            />
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-2">Maximum 150 characters</p>
                        </div>
                    </div>

                    <hr class="border-slate-200" />

                    <!-- Access Control Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-700 font-semibold text-sm">2</span>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">Access Control</h2>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-sm font-semibold text-slate-800" for="tenant_status">
                                Tenant Status
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select
                                id="tenant_status"
                                name="tenant_status"
                                x-model="status"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-900 transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none appearance-none cursor-pointer bg-white"
                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22currentColor%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 14l-7 7m0 0l-7-7m7 7V3%22/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; padding-right: 2.75rem;"
                            >
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            @error('tenant_status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            <div class="p-3 rounded-lg bg-blue-50 border border-blue-200">
                                <p class="text-xs text-blue-700"><span class="font-semibold">â„¹ï¸ Note:</span> Controls tenant access only. Subscription and billing are not affected by status changes.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Conditional Suspension Details -->
                    <div x-show="status === 'suspended'" x-cloak class="space-y-6 pt-4 border-t border-slate-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                <span class="text-red-700 font-semibold text-sm">3</span>
                            </div>
                            <h2 class="text-lg font-semibold text-slate-900">Suspension Details</h2>
                        </div>

                        <div class="space-y-6 p-4 rounded-lg bg-red-50 border border-red-200">
                            <!-- Suspension Type -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-slate-800" for="suspend_reason_type">
                                    Suspension Reason Type
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <select
                                    id="suspend_reason_type"
                                    name="suspend_reason_type"
                                    class="w-full px-4 py-3 rounded-lg border border-red-300 text-slate-900 transition-all focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none appearance-none cursor-pointer bg-white"
                                    style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22currentColor%22><path stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%222%22 d=%22M19 14l-7 7m0 0l-7-7m7 7V3%22/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 1.25rem; padding-right: 2.75rem;"
                                >
                                    <option value="">-- Select reason type --</option>
                                    <option value="policy_violation" {{ old('suspend_reason_type', $company->suspend_reason_type ?? '') === 'policy_violation' ? 'selected' : '' }}>Policy Violation</option>
                                    <option value="admin_action" {{ old('suspend_reason_type', $company->suspend_reason_type ?? '') === 'admin_action' ? 'selected' : '' }}>Admin Action</option>
                                    <option value="other" {{ old('suspend_reason_type', $company->suspend_reason_type ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('suspend_reason_type')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Additional Notes -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-slate-800" for="suspend_reason">
                                    Catatan Tambahan
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <textarea
                                    id="suspend_reason"
                                    name="suspend_reason"
                                    rows="4"
                                    minlength="10"
                                    maxlength="500"
                                    class="w-full px-4 py-3 rounded-lg border border-red-300 text-slate-900 placeholder-slate-400 transition-all focus:border-red-500 focus:ring-2 focus:ring-red-200 focus:outline-none"
                                    placeholder="Provide detailed explanation of the suspension (minimum 10 characters)..."
                                >{{ old('suspend_reason', $company->suspend_reason ?? '') }}</textarea>
                                @error('suspend_reason')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-slate-500">Minimum 10, maximum 500 characters. Used for internal audit and tenant notification.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-200">
                        <a
                            href="{{ route('super_admin.tenants.index') }}"
                            class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium text-sm hover:bg-slate-50 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-medium text-sm shadow-md hover:bg-indigo-700 active:bg-indigo-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Text -->
            <div class="mt-6 p-4 rounded-lg bg-slate-100 border border-slate-200">
                <p class="text-xs text-slate-600"><span class="font-semibold">ðŸ’¡ Tip:</span> Changes take effect immediately. Suspension prevents tenant login but does not affect existing data or subscriptions.</p>
            </div>
        </div>
    </div>
</x-app-layout>






