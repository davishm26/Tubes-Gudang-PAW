<x-app-layout>
    <x-slot name="header">
        {{-- Tampilkan nama kategori yang sedang diedit di header --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori: ') . $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- === TAMBAHAN: NOTIFIKASI SUKSES === --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    {{-- =================================== --}}

                    {{-- Form untuk Edit Kategori --}}
                    <form method="POST" action="{{ route('categories.update', $category->id) }}">
                        @csrf
                        @method('PUT') {{-- WAJIB: Spoofing method untuk UPDATE --}}

                        {{-- Field Nama Kategori --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>

                            {{-- Tampilkan Error Validasi --}}
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                Perbarui Kategori
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
