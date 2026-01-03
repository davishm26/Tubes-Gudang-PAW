<x-app-layout>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profil') }}
            </h2>
            @if($isDemo)
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                    Demo Mode - Read Only
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if($isDemo)
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>🎭 Demo Mode:</strong> Tampilan sama seperti mode real, tetapi semua perubahan tidak akan disimpan.
                    </p>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            @if(!$isDemo)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @elseif($isDemo)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900">Ubah Password</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Fitur ini tidak tersedia dalam mode demo.
                        </p>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900">Hapus Akun</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Fitur ini tidak tersedia dalam mode demo.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
