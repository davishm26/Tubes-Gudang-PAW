<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">{{ __('Notifikasi') }}</h1>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <span class="flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                    <span class="h-2 w-2 rounded-full {{ $notifications->whereNull('read_at')->count() ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    {{ $notifications->whereNull('read_at')->count() }} {{ __('belum dibaca') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($notifications->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-600">
                        <p class="text-lg font-semibold">{{ __('Belum ada notifikasi untuk Anda.') }}</p>
                        <p class="text-sm text-gray-500 mt-2">{{ __('Anda akan melihat notifikasi di sini ketika Super Admin mengirim pesan.') }}</p>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border {{ $notification->read_at ? 'border-gray-200' : 'border-indigo-200' }}">
                            <div class="p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $notification->read_at ? 'bg-gray-100 text-gray-600' : 'bg-indigo-50 text-indigo-700 border border-indigo-200' }}">
                                            {{ $notification->read_at ? __('Sudah dibaca') : __('Belum dibaca') }}
                                        </span>
                                        @if($notification->template)
                                            <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ ucwords(str_replace('_', ' ', $notification->template)) }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-gray-900 font-semibold leading-relaxed">{{ $notification->message }}</p>

                                    <div class="text-sm text-gray-500 flex items-center gap-2">
                                        <span>{{ __('Dari') }}: {{ optional($notification->sender)->name ?? 'System' }}</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                            @csrf
                                            <x-primary-button>{{ __('Tandai dibaca') }}</x-primary-button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-500">{{ __('Ditandai dibaca pada') }} {{ $notification->read_at->format('d M Y, H:i') }}</span>
                                    @endif

                                    @if($notification->template === 'subscription_expiry' && Auth::user()?->role === 'admin')
                                        <a
                                            href="{{ route('subscription.subscribe') }}"
                                            class="inline-flex items-center rounded-md bg-indigo-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Perpanjang Langganan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
