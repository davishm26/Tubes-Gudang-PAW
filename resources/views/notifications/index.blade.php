<x-app-layout>
    <x-slot name="title">Notifikasi - StockMaster</x-slot>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
        // Pertahankan collection asli; jika array, bungkus ke collection tanpa mengubah model
        $notificationsList = $notifications instanceof \Illuminate\Support\Collection
            ? $notifications
            : collect($notifications);
        $unreadCount = $notificationsList->whereNull('read_at')->count();
    @endphp

    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold text-white">{{ __('Notifikasi') }}</h1>
                    <div class="flex items-center gap-2">
                        @if($isDemo)
                            <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-sm font-medium">
                                Demo Mode
                            </span>
                        @endif
                        <span class="flex items-center gap-2 text-sm text-white/90">
                            <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 text-white border border-white/20">
                                <span class="h-2 w-2 rounded-full {{ $unreadCount ? 'bg-red-400' : 'bg-green-400' }}"></span>
                                {{ $unreadCount }} {{ __('belum dibaca') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($isDemo)
                <div class="mb-6 p-4 bg-[#E9F6F1] border border-[#C8E6DF] rounded-lg">
                    <p class="text-sm text-[#166B50]">
                        <strong>Demo Mode:</strong> Anda sedang melihat notifikasi demo. Total {{ count($notificationsArray) }} notifikasi contoh.
                    </p>
                </div>
            @endif

            @if($notificationsList->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold">{{ __('Belum ada notifikasi untuk Anda.') }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.') }}</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($notificationsList as $notification)
                        @php
                            $isRead = (data_get($notification, 'read_at')) ? true : false;
                            $notificationId = $notification->id ?? data_get($notification, 'id');
                        @endphp
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border {{ $isRead ? 'border-gray-200' : 'border-[#C8E6DF]' }}">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $isRead ? 'bg-gray-100 text-gray-600' : 'bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]' }}">
                                            {{ $isRead ? 'Dibaca' : 'Belum dibaca' }}
                                        </span>
                                        @if(data_get($notification, 'type') || data_get($notification, 'template'))
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                {{ data_get($notification, 'type', data_get($notification, 'template')) === 'success' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ data_get($notification, 'type', data_get($notification, 'template')) === 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ data_get($notification, 'type', data_get($notification, 'template')) === 'info' ? 'bg-[#E9F6F1] text-[#166B50] border border-[#C8E6DF]' : '' }}
                                                {{ (data_get($notification, 'template') && !data_get($notification, 'type')) ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                                @if(data_get($notification, 'type'))
                                                    {{ ucfirst(data_get($notification, 'type')) }}
                                                @elseif(data_get($notification, 'template'))
                                                    {{ ucwords(str_replace('_', ' ', data_get($notification, 'template'))) }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    @if(data_get($notification, 'title'))
                                        <p class="text-gray-900 font-semibold leading-relaxed">{{ data_get($notification, 'title') }}</p>
                                        <p class="text-gray-700">{{ data_get($notification, 'message', '') }}</p>
                                    @else
                                        <p class="text-gray-900 font-semibold leading-relaxed">{{ data_get($notification, 'message', data_get($notification, 'message')) ?? 'Notifikasi' }}</p>
                                    @endif

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        @if(data_get($notification, 'user_name'))
                                            <span>{{ __('Dari') }}: {{ data_get($notification, 'user_name') }}</span>
                                        @else
                                            <span>{{ __('Dari') }}: {{ optional(data_get($notification, 'sender'))->name ?? optional($notification->sender ?? null)->name ?? 'System' }}</span>
                                        @endif
                                        <span class="text-gray-300">•</span>
                                        @if(data_get($notification, 'created_at'))
                                            <span>{{ \Carbon\Carbon::parse(data_get($notification, 'created_at'))->locale(config('app.locale'))->diffForHumans() }}</span>
                                        @else
                                            <span>{{ optional($notification->created_at)->locale(config('app.locale'))->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if(!$isDemo)
                                    <div class="flex items-center gap-3">
                                        @if(!$isRead && $notificationId)
                                            <form method="POST" action="{{ route('notifications.markAsRead', $notificationId) }}">
                                                @csrf
                                                <x-primary-button>{{ __('Tandai dibaca') }}</x-primary-button>
                                            </form>
                                        @elseif($isRead)
                                            <span class="text-xs text-gray-500">{{ __('Ditandai dibaca pada') }}
                                                @if(data_get($notification, 'read_at'))
                                                    {{ \Carbon\Carbon::parse(data_get($notification, 'read_at'))->format('d M Y, H:i') }}
                                                @else
                                                    {{ optional($notification->read_at)->format('d M Y, H:i') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-xs text-red-600">ID notifikasi tidak ditemukan.</span>
                                        @endif

                                        @if((data_get($notification, 'template') === 'subscription_expiry' || (isset($notification->template) && $notification->template === 'subscription_expiry')) && Auth::user()?->role === 'admin')
                                            <a
                                                href="{{ route('subscription.subscribe') }}"
                                                class="inline-flex items-center rounded-md bg-[#166B50] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#0F4C37] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2"
                                            >
                                                Perpanjang Langganan
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>






