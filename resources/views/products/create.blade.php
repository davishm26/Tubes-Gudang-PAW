<x-app-layout>
    <x-slot name="title">Tambah Produk - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-[#E5E7EB]">

                    {{-- WAJIB: enctype untuk File Upload --}}
                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Field Nama Produk --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Produk</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                    class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                                @error('name')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field SKU --}}
                            <div>
                                <label for="sku" class="block text-sm font-semibold text-slate-700">SKU (Kode Produk)</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                    class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                                @error('sku')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Kategori (Relasi) --}}
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-slate-700">Kategori</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Pemasok (Relasi) --}}
                            <div>
                                <label for="supplier_id" class="block text-sm font-semibold text-slate-700">Pemasok</label>
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                                    <option value="" disabled selected>-- Pilih Pemasok --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Stok Awal --}}
                            <div>
                                <label for="stock" class="block text-sm font-semibold text-slate-700">Stok Awal</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                                    class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A]" required>
                                @error('stock')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Gambar Produk (File Upload) --}}
                            <div>
                                <label for="image" class="block text-sm font-semibold text-slate-700">Gambar Produk</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#E9F6F1] file:text-[#1F8F6A] hover:file:bg-[#F0FAF7]">
                                @error('image')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6 border-t border-[#E5E7EB] pt-4">
                            <a href="{{ route('products.index') }}" class="text-sm text-slate-600 hover:text-slate-900 mr-4 font-medium">
                                Batal
                            </a>
                            <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150">
                                Simpan Produk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>






