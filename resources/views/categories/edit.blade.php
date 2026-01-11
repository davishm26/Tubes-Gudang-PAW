<x-app-layout>
    <x-slot name="title">Edit Kategori - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Tampilkan nama kategori yang sedang diedit di header --}}
                <h2 class="font-semibold text-2xl text-white leading-tight">
                    {{ __('Edit Kategori: ') . $category->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-[#E5E7EB]">

                    {{-- === TAMBAHAN: NOTIFIKASI SUKSES === --}}
                    @if (session('success'))
                        <div class="bg-[#E9F6F1] border border-[#C8E6DF] text-[#1F8F6A] px-4 py-3 rounded-lg relative mb-4" role="alert">
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
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Kategori</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A] sm:text-sm"
                                   required>

                            {{-- Tampilkan Error Validasi --}}
                            @error('name')
                                <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('categories.index') }}" class="text-sm text-slate-600 hover:text-slate-900 mr-4 font-medium">
                                Batal
                            </a>

                            <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150">
                                Perbarui Kategori
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>






