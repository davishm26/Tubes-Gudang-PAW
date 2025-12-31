<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tambah Pengguna') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" class="mt-1 block w-full border rounded px-3 py-2" required>
                            @error('password')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full border rounded px-3 py-2" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 block w-full border rounded px-3 py-2">
                                @foreach($roles as $r)
                                    <option value="{{ $r }}" {{ old('role')===$r ? 'selected' : '' }}>{{ $r === 'staf' ? 'Staf' : ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            @error('role')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('users.index') }}" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
