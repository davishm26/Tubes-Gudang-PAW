<x-app-layout>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Manajemen User') }}</h2>
            @if($isDemo)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    Demo Mode - Read Only
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($isDemo)
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>🎭 Demo Mode:</strong> Tampilan sama seperti mode real, tetapi semua perubahan tidak akan disimpan.
                    </p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="mb-4 p-3 rounded bg-blue-50 text-blue-800 border border-blue-200">
                        {{ session('info') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium">Daftar Pengguna</h3>
                    @if(!$isDemo)
                    <a href="{{ route('users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded text-sm">
                        + Tambah Pengguna
                    </a>
                    @endif
                </div>

                {{-- Form Pencarian --}}
                <form method="GET" action="{{ route('users.index') }}" class="mb-4">
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user (nama, email, role)..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">
                            Cari
                        </button>
                    </div>
                </form>

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
                                    <td class="py-2 px-4">{{ $user->name ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $user->email ?? '-' }}</td>
                                    <td class="py-2 px-4">{{ $user->role === 'staf' ? 'Staf' : ($user->role ?? '-') }}</td>
                                    <td class="py-2 px-4 text-right">
                                        @if(!$isDemo)
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus pengguna ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-gray-400 text-sm">Read Only</span>
                                        @endif
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
