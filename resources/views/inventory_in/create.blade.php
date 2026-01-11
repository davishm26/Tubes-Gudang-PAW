<x-app-layout>
    <x-slot name="title">Tambah Barang Masuk - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">
                    {{ __('Catat Stok Masuk Baru') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Pesan Sukses atau Error dari Controller --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('inventory-in.store') }}">
                        @csrf

                        {{-- Field Produk (Dropdown Relasi) --}}
                        <div class="mb-4">
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Produk</label>
                            <select name="product_id" id="product_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                                <option value="" disabled selected>-- Pilih Produk --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stok Saat Ini: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- FIELD PEMASOK (BARU) --}}
                        <div class="mb-4">
                            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Pemasok (Supplier)</label>
                            <select name="supplier_id" id="supplier_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Pemasok (Opsional) --</option>
                                {{-- Pastikan Controller mengirimkan variabel $suppliers --}}
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('supplier_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Field Jumlah (Quantity) --}}
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah Masuk</label>
                            <input type="number"
                                    name="quantity"
                                    id="quantity"
                                    value="{{ old('quantity') }}"
                                    min="1"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                            @error('quantity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Field Tanggal --}}
                        <div class="mb-4">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                            <input type="date"
                                    name="date"
                                    id="date"
                                    value="{{ old('date', date('Y-m-d')) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    required>
                            @error('date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- FIELD KETERANGAN (BARU) --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Contoh: Penerimaan barang berdasarkan PO #12345">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                Catat Stok Masuk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Auto-fill Supplier --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('product_id');
            const supplierSelect = document.getElementById('supplier_id');

            // Data produk dengan supplier_id
            const productSuppliers = @json($products->pluck('supplier_id', 'id'));

            productSelect.addEventListener('change', function() {
                const selectedProductId = this.value;
                const supplierId = productSuppliers[selectedProductId];

                if (supplierId) {
                    supplierSelect.value = supplierId;
                } else {
                    supplierSelect.value = '';
                }
            });
        });
    </script>
</x-app-layout>






