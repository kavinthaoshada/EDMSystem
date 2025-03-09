@extends('layouts.employee')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-md mt-3">
        <h1 class="text-2xl font-bold mb-4">All Notifications</h1>

        <div class="space-y-4">
            @foreach ($notifications as $notification)
                <div class="p-4 mb-3 border rounded-md shadow-sm {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-50' }}">
                    <p>{{ $notification->data['message'] ?? 'Notification' }}</p>
                    <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>

                    @if (!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 text-sm">Mark as Read</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
    @endsection
