<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('categories.create') }}" class="bg-indigo-500 text-white p-2 rounded mb-4 inline-block">Tambah Kategori</a>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Nama Kategori</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $category->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $category->name }}</td>
                                <td class="py-2 px-4 border-b">
                                    {{-- 1. TOMBOL EDIT --}}
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                                        Edit
                                    </a>

                                    {{-- 2. FORM HAPUS --}}
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                        {{-- Token Keamanan Wajib --}}
                                        @csrf

                                        {{-- Menggunakan method DELETE (persyaratan Laravel Resource Controller) --}}
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kategori: {{ $category->name }}?')"
                                            class="text-red-600 hover:text-red-900">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
