<x-app-layout>
    <x-slot name="title">Edit Produk - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Edit Produk: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-emerald-200">

                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Field Nama Produk --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Produk</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $product->name) }}"
                                    class="mt-1 block w-full border border-emerald-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                @error('name')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field SKU --}}
                            <div>
                                <label for="sku" class="block text-sm font-semibold text-slate-700">SKU (Kode Produk)</label>
                                <input type="text" name="sku" id="sku"
                                    value="{{ old('sku', $product->sku) }}"
                                    class="mt-1 block w-full border border-emerald-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                @error('sku')
                                    <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Kategori (Relasi) --}}
                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-slate-700">Kategori</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full border border-emerald-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border border-emerald-200 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                                    <option value="" disabled>-- Pilih Pemasok --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Stok (PERBAIKAN: Menambahkan name="stock" dan readonly) --}}
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Saat Ini</label>
                                <input type="number" name="stock" id="stock" value="{{ $product->stock }}" readonly
                                    class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm cursor-not-allowed">
                                <p class="text-xs text-gray-500 mt-1">Stok hanya diubah melalui menu Stok.</p>
                                @error('stock')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Gambar Produk (File Upload) --}}
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Ganti Gambar Produk</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                @error('image')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror

                                {{-- Preview Gambar Lama --}}
                                @php
                                    $previewPath = $product->image_path ?? $product->image ?? null;
                                    $previewUrl = $previewPath
                                        ? (\Illuminate\Support\Str::startsWith($previewPath, ['http://', 'https://'])
                                            ? $previewPath
                                            : Storage::url($previewPath))
                                        : null;
                                @endphp
                                @if($previewUrl)
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-500 mb-1">Gambar Lama:</p>
                                        <img src="{{ $previewUrl }}" alt="Gambar Lama" class="w-20 h-20 object-cover rounded">
                                    </div>
                                @endif
                            </div>

                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6 border-t pt-4">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                Perbarui Produk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
