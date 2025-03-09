<div class="relative" x-data="{ open: false }">
    <!-- Bell Icon Button -->
    <button @click="open = !open" class="relative flex items-center p-2 sm:p-0 text-gray-600 hover:text-blue-600 focus:outline-none">
        
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" 
            class="w-6 h-6 hidden sm:block">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
        </svg>
    
        <span class="text-sm font-semibold sm:hidden">Notification</span>
    
        @if ($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div x-show="open" @click.away="open = false"
        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50 sm:w-80 md:w-96"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95">

        <div class="p-4 text-gray-800 font-bold border-b">
            Notifications
        </div>

        <div class="max-h-60 overflow-y-auto">
            @forelse ($notifications as $notification)
                <div class="px-4 py-3 border-b hover:bg-gray-100 flex items-center justify-between">
                    <div class="w-4/5">
                        <p class="text-sm truncate">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                        <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <button wire:click="markAsRead('{{ $notification->id }}')"
                        class="text-blue-600 text-xs hover:underline">
                        Mark as Read
                    </button>
                </div>
            @empty
                <p class="p-4 text-center text-gray-500">No new notifications</p>
            @endforelse
        </div>

        <div class="p-2 text-center border-t">
            <a href="{{ route('notifications.index') }}" class="text-blue-600 text-sm font-bold hover:underline">
                View All
            </a>
        </div>
    </div>
</div>
