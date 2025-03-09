<div class="p-6">
    <h2 class="text-lg font-semibold text-gray-900">{{ json_decode($notification->data, true)['message'] ?? 'No Subject' }}</h2>

    <p class="text-gray-600 mt-2">Sent to: <strong>{{ $notification->notifiable->name }}</strong></p>
    <p class="text-gray-600">Received: {{ $notification->created_at->format('F j, Y, g:i A') }}</p>

    <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-300">
        <p class="text-gray-800">
            {{ json_decode($notification->data, true)['message'] ?? 'No Message Content' }}
        </p>
    </div>

    @if ($notification->read_at)
        <p class="text-green-500 mt-2">Read on: {{ $notification->read_at->format('F j, Y, g:i A') }}</p>
    @else
        <p class="text-red-500 mt-2">Unread</p>
    @endif
</div>
