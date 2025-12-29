<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tenant</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('super_admin.tenants.update', $company) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block">Name</label>
                        <input name="name" value="{{ old('name', $company->name) }}" class="border p-2 w-full" required />
                    </div>
                    <div class="mb-4">
                        <label class="block">Subscription Status</label>
                        <select name="subscription_status" class="border p-2 w-full" required>
                            <option value="active" {{ old('subscription_status', $company->subscription_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('subscription_status', $company->subscription_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-blue-600 text-white px-3 py-2 rounded">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
