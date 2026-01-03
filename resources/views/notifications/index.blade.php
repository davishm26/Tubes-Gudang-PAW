<x-app-layout>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
        // Convert notifications to array if it's a collection
        $notificationsArray = is_array($notifications) ? $notifications : $notifications->toArray();
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">{{ __('Notifikasi') }}</h1>
            <div class="flex items-center gap-2">
                @if($isDemo)
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                        Demo Mode
                    </span>
                @endif
                <span class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                        <span class="h-2 w-2 rounded-full {{ collect($notificationsArray)->whereNull('read_at')->count() ? 'bg-red-500' : 'bg-green-500' }}"></span>
                        {{ collect($notificationsArray)->whereNull('read_at')->count() }} {{ __('belum dibaca') }}
                    </span>
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($isDemo)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Demo Mode:</strong> Anda sedang melihat notifikasi demo. Total {{ count($notificationsArray) }} notifikasi contoh.
                    </p>
                </div>
            @endif

            @if(empty($notificationsArray))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold">{{ __('Belum ada notifikasi untuk Anda.') }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.') }}</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($notificationsArray as $notification)
                        @php
                            $isRead = ($notification['read_at'] ?? $notification->read_at ?? null) ? true : false;
                        @endphp
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border {{ $isRead ? 'border-gray-200' : 'border-indigo-200' }}">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $isRead ? 'bg-gray-100 text-gray-600' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                            {{ $isRead ? 'Dibaca' : 'Belum dibaca' }}
                                        </span>
                                        @if(isset($notification['type']) || isset($notification->template))
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                                {{ ($notification['type'] ?? $notification->template ?? null) === 'success' ? 'bg-green-100 text-green-700' : '' }}
                                                {{ ($notification['type'] ?? $notification->template ?? null) === 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                {{ ($notification['type'] ?? $notification->template ?? null) === 'info' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ isset($notification->template) && !isset($notification['type']) ? 'bg-slate-100 text-slate-700 border border-slate-200' : '' }}">
                                                @if(isset($notification['type']))
                                                    {{ ucfirst($notification['type']) }}
                                                @elseif(isset($notification->template))
                                                    {{ ucwords(str_replace('_', ' ', $notification->template)) }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    @if(isset($notification['title']))
                                        <p class="text-gray-900 font-semibold leading-relaxed">{{ $notification['title'] }}</p>
                                        <p class="text-gray-700">{{ $notification['message'] ?? '' }}</p>
                                    @else
                                        <p class="text-gray-900 font-semibold leading-relaxed">{{ $notification['message'] ?? $notification->message ?? 'Notifikasi' }}</p>
                                    @endif

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        @if(isset($notification['user_name']))
                                            <span>{{ __('Dari') }}: {{ $notification['user_name'] }}</span>
                                        @else
                                            <span>{{ __('Dari') }}: {{ optional($notification->sender ?? null)->name ?? 'System' }}</span>
                                        @endif
                                        <span class="text-gray-300">•</span>
                                        @if(isset($notification['created_at']))
                                            <span>{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</span>
                                        @else
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if(!$isDemo)
                                    <div class="flex items-center gap-3">
                                        @if(!$isRead)
                                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id ?? $notification['id']) }}">
                                                @csrf
                                                <x-primary-button>{{ __('Tandai dibaca') }}</x-primary-button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-500">{{ __('Ditandai dibaca pada') }}
                                                @if(isset($notification['read_at']))
                                                    {{ \Carbon\Carbon::parse($notification['read_at'])->format('d M Y, H:i') }}
                                                @else
                                                    {{ $notification->read_at->format('d M Y, H:i') }}
                                                @endif
                                            </span>
                                        @endif

                                        @if((isset($notification['template']) && $notification['template'] === 'subscription_expiry' || isset($notification->template) && $notification->template === 'subscription_expiry') && Auth::user()?->role === 'admin')
                                            <a
                                                href="{{ route('subscription.subscribe') }}"
                                                class="inline-flex items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
