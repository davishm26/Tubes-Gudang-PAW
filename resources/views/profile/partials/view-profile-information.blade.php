@php
    $user = $user ?? Auth::user();
    $isArray = is_array($user);
@endphp

<header>
    <h2 class="text-lg font-medium text-gray-900">
        {{ __('Informasi Profil') }}
    </h2>

    <p class="mt-1 text-sm text-gray-600">
        {{ __("Lihat informasi profil Anda. Dalam mode demo, data tidak dapat diubah.") }}
    </p>
</header>

<div class="mt-6 space-y-6">
    <div>
        <x-input-label for="name" :value="__('Nama')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $isArray ? ($user['name'] ?? '-') : $user->name }}
        </div>
    </div>

    <div>
        <x-input-label for="email" :value="__('Email')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $isArray ? ($user['email'] ?? '-') : $user->email }}
        </div>
    </div>

    @if($isArray && isset($user['phone']))
    <div>
        <x-input-label for="phone" :value="__('Telepon')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $user['phone'] ?? '-' }}
        </div>
    </div>
    @endif

    @if($isArray && isset($user['company']))
    <div>
        <x-input-label for="company" :value="__('Perusahaan')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $user['company'] ?? '-' }}
        </div>
    </div>
    @endif

    @if($isArray && isset($user['role']))
    <div>
        <x-input-label for="role" :value="__('Role')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            <span class="px-2 py-1 rounded text-sm font-medium {{ $user['role'] === 'admin' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                {{ ucfirst($user['role']) }}
            </span>
        </div>
    </div>
    @endif

    @if($isArray && isset($user['department']))
    <div>
        <x-input-label for="department" :value="__('Departemen')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $user['department'] ?? '-' }}
        </div>
    </div>
    @endif

    @if($isArray && isset($user['address']))
    <div>
        <x-input-label for="address" :value="__('Alamat')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">
            {{ $user['address'] ?? '-' }}
        </div>
    </div>
    @endif

    @if($isArray && isset($user['about']))
    <div>
        <x-input-label for="about" :value="__('Tentang')" />
        <div class="mt-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700 whitespace-pre-wrap">
            {{ $user['about'] ?? '-' }}
        </div>
    </div>
    @endif

    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-600">
            <p><strong>Profile Status:</strong> Demo Mode (Read Only)</p>
            @if($isArray && isset($user['updated_at']))
                <p class="text-xs text-gray-500 mt-1">
                    Last updated: {{ \Carbon\Carbon::parse($user['updated_at'])->format('d M Y, H:i') }}
                </p>
            @elseif(!$isArray && $user->updated_at)
                <p class="text-xs text-gray-500 mt-1">
                    Last updated: {{ $user->updated_at->format('d M Y, H:i') }}
                </p>
            @endif
        </div>
    </div>
</div>






