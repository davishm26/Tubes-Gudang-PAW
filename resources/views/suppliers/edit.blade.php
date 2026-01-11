<x-app-layout>
    <x-slot name="title">Edit Pemasok - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">
                    {{ __('Edit Pemasok: ') . $supplier->name }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-[#E5E7EB]">

                    {{-- Form untuk Edit Pemasok --}}
                    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                        @csrf
                        @method('PUT') {{-- WAJIB: Spoofing method untuk UPDATE --}}

                        {{-- Field Nama Pemasok --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-semibold text-slate-700">Nama Pemasok</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   {{-- Mengisi nilai lama atau nilai dari database --}}
                                   value="{{ old('name', $supplier->name) }}"
                                   class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A] sm:text-sm"
                                   required>

                            @error('name')
                                <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Field Kontak Pemasok --}}
                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-semibold text-slate-700">Kontak Pemasok (Opsional)</label>
                            <input type="text"
                                   name="contact"
                                   id="contact"
                                   value="{{ old('contact', $supplier->contact) }}"
                                   class="mt-1 block w-full border border-[#E5E7EB] rounded-lg shadow-sm focus:ring-2 focus:ring-[#1F8F6A] focus:border-[#1F8F6A] sm:text-sm">

                            @error('contact')
                                <p class="text-sm text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('suppliers.index') }}" class="text-sm text-slate-600 hover:text-slate-900 mr-4 font-medium">
                                Batal
                            </a>

                            <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-150">
                                Perbarui Pemasok
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>






