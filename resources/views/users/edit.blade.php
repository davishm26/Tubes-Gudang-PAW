<x-app-layout>
    <x-slot name="title">Edit Pengguna - StockMaster</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">{{ __('Edit Pengguna') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">

                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border border-emerald-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            @error('name')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border border-emerald-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
                            @error('email')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Password (biarkan kosong untuk tidak mengubah)</label>
                            <input type="password" name="password" class="mt-1 block w-full border border-emerald-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('password')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 block w-full border border-emerald-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Role</label>
                            <select name="role" class="mt-1 block w-full border border-emerald-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach($roles as $r)
                                    <option value="{{ $r }}" {{ old('role', $user->role)===$r ? 'selected' : '' }}>{{ $r === 'staf' ? 'Staf' : ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            @error('role')<p class="text-sm text-rose-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex justify-end gap-3 mt-4">
                            <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 border border-slate-300 rounded-xl hover:bg-slate-50 transition">Batal</a>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-xl font-semibold transition">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
