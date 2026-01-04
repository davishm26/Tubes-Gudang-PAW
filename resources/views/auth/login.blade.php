<x-guest-layout>
    <x-slot name="title">Masuk - StockMaster</x-slot>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Alamat Email -->
        <div class="mt-1>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-0.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-0.5" />
        </div>

        <!-- Kata Sandi -->
        <div class="mt-0.01>
            <x-input-label for="password" :value="__('Kata Sandi')" />

            <x-text-input id="password" class="block mt-0.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-0.5" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-[#C8E6DF] text-[#1F8F6A] shadow-sm focus:ring-[#1F8F6A]" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-[#1F2937] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1F8F6A]" href="{{ route('password.request') }}">
                    {{ __('Lupa kata sandi?') }}
                </a>
            @endif

            <div class="flex items-center space-x-4">
                <a href="{{ route('subscription.subscribe') }}" class="underline text-sm text-gray-600 hover:text-[#1F2937] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1F8F6A]">
                    {{ __('Belum punya akun? Daftar') }}
                </a>

                <x-primary-button>
                    {{ __('Masuk') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>






