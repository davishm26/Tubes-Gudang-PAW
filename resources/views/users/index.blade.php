<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen User') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Daftar Pengguna</h3>

                <div class="mb-4 flex justify-between items-center">
                    <h4 class="text-md font-semibold">Semua Pengguna</h4>
                    <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-sm">
                        + Tambah Pengguna
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="py-2 px-4">{{ $user->name }}</td>
                                    <td class="py-2 px-4">{{ $user->email }}</td>
                                    <td class="py-2 px-4">{{ $user->role ?? '-' }}</td>
                                    <td class="py-2 px-4 text-right">
                                        <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-4 px-4" colspan="4">Belum ada pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
