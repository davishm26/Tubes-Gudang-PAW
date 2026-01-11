<div {{ $attributes->merge(['class' => 'flex items-center select-none']) }} style="gap: 12px;">
    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">
        <circle cx="32" cy="32" r="30" fill="#1F8F6A"/>
        <circle cx="32" cy="32" r="30" fill="url(#grad1)" opacity="0.3"/>

        <rect x="18" y="18" width="28" height="28" rx="4" fill="white" opacity="0.9"/>
        <rect x="18" y="18" width="28" height="28" rx="4" fill="url(#grad2)" opacity="0.2"/>
        <rect x="23" y="24" width="18" height="3" rx="1.5" fill="#1F8F6A" opacity="0.5"/>
        <rect x="23" y="30" width="12" height="3" rx="1.5" fill="#1F8F6A" opacity="0.5"/>
        <path d="M26 38 L30 42 L38 34" stroke="#1F8F6A" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>

        <defs>
            <linearGradient id="grad1" x1="0" y1="0" x2="64" y2="64">
                <stop offset="0%" stop-color="white" stop-opacity="0.3"/>
                <stop offset="100%" stop-color="black" stop-opacity="0.1"/>
            </linearGradient>
            <linearGradient id="grad2" x1="18" y1="18" x2="46" y2="46">
                <stop offset="0%" stop-color="black" stop-opacity="0.1"/>
                <stop offset="100%" stop-color="transparent"/>
            </linearGradient>
        </defs>
    </svg>
    <div class="flex flex-col justify-center" style="line-height: 1.1;">
        <span class="font-extrabold" style="font-size: 28px; letter-spacing: -0.5px; color: #2c3e50;">StockMaster</span>
        <span class="font-bold uppercase" style="font-size: 11px; color: #1F8F6A; letter-spacing: 1px; margin-top: 2px;">Inventory System</span>
    </div>
</div>






