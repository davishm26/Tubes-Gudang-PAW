<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#1F8F6A] border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-[#166B50] focus:bg-[#166B50] active:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>






