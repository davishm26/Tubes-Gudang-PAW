<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Tenant</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('super_admin.tenants.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block">Name</label>
                        <input name="name" class="border p-2 w-full" required />
                    </div>
                    <div class="mb-4">
                        <label class="block">Subscription Status</label>
                        <input name="subscription_status" class="border p-2 w-full" />
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-blue-600 text-white px-3 py-2 rounded">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
