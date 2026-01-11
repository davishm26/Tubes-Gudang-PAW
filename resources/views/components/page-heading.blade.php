@props([
    'title' => 'Beranda',
    'subtitle' => null,
    'icon' => true,
    'variant' => 'default', // default | minimal
])

@if($variant === 'minimal')
    <div class="flex items-center justify-between py-2">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                {{ $title }}
            </h2>
            <div class="h-1 w-16 bg-[#1F8F6A] rounded-full mt-1"></div>
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-2">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            {{ $actions ?? '' }}
        </div>
    </div>
@else
    <div class="flex items-center justify-between py-2">
        <div class="flex items-center gap-4">
            @if($icon)
                <div class="h-10 w-10 rounded-full bg-[#1F8F6A] flex items-center justify-center text-white shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 9.75A.75.75 0 0 1 3.75 9h16.5a.75.75 0 0 1 .75.75V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9.75Z"/>
                        <path d="M21 8H3l8.485-5.657a1 1 0 0 1 1.03 0L21 8Z"/>
                    </svg>
                </div>
            @endif
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight bg-clip-text text-transparent bg-gradient-to-r from-[#1F8F6A] to-[#14B8A6]">
                    {{ $title }}
                </h2>
                @if($subtitle)
                    <p class="text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{ $actions ?? '' }}
        </div>
    </div>
@endif
