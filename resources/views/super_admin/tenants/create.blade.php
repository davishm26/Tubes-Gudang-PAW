<x-app-layout>
    <x-slot name="title">Tambah Tenant - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">Buat Penyewa</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <form method="POST" action="{{ route('super_admin.tenants.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block">Nama</label>
                        <input name="name" class="border p-2 w-full" required />
                    </div>
                    <div class="mb-4">
                        <label class="block">Status Langganan</label>
                        <input name="subscription_status" class="border p-2 w-full" />
                    </div>
                    <div class="flex justify-end">
                        <button class="bg-blue-600 text-white px-3 py-2 rounded">Buat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>






