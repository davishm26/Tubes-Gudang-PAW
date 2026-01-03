<section>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
    @endphp

    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    @if($isDemo)
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" onsubmit="event.preventDefault(); alert('Perubahan tidak dapat disimpan dalam mode demo.'); return false;">
    @else
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
    @endif
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama')" />
            @if($isDemo)
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full opacity-60" :value="old('name', $user->name)" required autofocus autocomplete="name" disabled />
            @else
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            @if($isDemo)
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full opacity-60" :value="old('email', $user->email)" required autocomplete="username" disabled />
            @else
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (!$isDemo && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            @if($isDemo)
            <x-primary-button disabled>{{ __('Simpan') }}</x-primary-button>
            @else
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
            @endif

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
