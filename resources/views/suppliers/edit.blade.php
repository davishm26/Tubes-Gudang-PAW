<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pemasok: ') . $supplier->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Form untuk Edit Pemasok --}}
                    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
                        @csrf
                        @method('PUT') {{-- WAJIB: Spoofing method untuk UPDATE --}}

                        {{-- Field Nama Pemasok --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Pemasok</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   {{-- Mengisi nilai lama atau nilai dari database --}}
                                   value="{{ old('name', $supplier->name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   required>

                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Field Kontak Pemasok --}}
                        <div class="mb-4">
                            <label for="contact" class="block text-sm font-medium text-gray-700">Kontak Pemasok (Opsional)</label>
                            <input type="text"
                                   name="contact"
                                   id="contact"
                                   value="{{ old('contact', $supplier->contact) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

                            @error('contact')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm transition duration-150">
                                Perbarui Pemasok
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>