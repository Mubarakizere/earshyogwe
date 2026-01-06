<div class="relative ml-3" x-data="{ open: false }">
    <button @click="open = ! open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-600 ring-2 ring-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" 
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50 ring-1 ring-black ring-opacity-5"
         style="display: none;">
        <div class="py-2">
            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-700">Notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:text-blue-800">Mark all read</button>
                    </form>
                @endif
            </div>
            
            <div class="max-h-64 overflow-y-auto">
                @forelse(auth()->user()->unreadNotifications as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 hover:bg-gray-50 transition duration-150 ease-in-out border-b border-gray-50 last:border-0">
                        <p class="text-sm text-gray-800 font-semibold">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </a>
                @empty
                    <div class="px-4 py-3 text-center text-sm text-gray-500">
                        No new notifications
                    </div>
                @endforelse
            </div>
            
            <a href="{{ route('notifications.index') }}" class="block text-center px-4 py-2 border-t border-gray-100 bg-gray-50 text-xs font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition">
                View all notifications
            </a>
        </div>
    </div>
</div>
