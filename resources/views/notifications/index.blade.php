@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Notifikasi</h1>

    @if($notifications->count() > 0)
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="bg-white shadow-md rounded-lg p-6 {{ $notification->isRead() ? 'border-l-4 border-green-500' : 'border-l-4 border-blue-500' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="text-gray-800 mb-2">{{ $notification->message }}</p>
                            <p class="text-sm text-gray-500">
                                Dari: {{ $notification->sender->name }} |
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                            @if($notification->template)
                                <p class="text-xs text-gray-400 mt-1">Template: {{ $notification->template }}</p>
                            @endif
                        </div>
                        @if(!$notification->isRead())
                            <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="ml-4">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Tandai Dibaca
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Tidak ada notifikasi.</p>
    @endif
</div>
@endsection
