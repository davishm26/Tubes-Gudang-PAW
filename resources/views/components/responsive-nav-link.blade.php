@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-500 text-start text-base font-medium text-[#166B50] bg-[#E9F6F1] focus:outline-none focus:text-[#1F2937] focus:bg-[#F0FAF7] focus:border-emerald-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-600 hover:text-[#166B50] hover:bg-[#E9F6F1] hover:border-[#C8E6DF] focus:outline-none focus:text-[#166B50] focus:bg-[#E9F6F1] focus:border-[#C8E6DF] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>






