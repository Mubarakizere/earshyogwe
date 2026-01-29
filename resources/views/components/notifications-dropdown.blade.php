<div class="relative ml-3" x-data="{ open: false, count: {{ auth()->user()->unreadNotifications->count() }} }">
    {{-- Notification Bell Button --}}
    <button @click="open = !open" 
            class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
        <span class="sr-only">View notifications</span>
        
        {{-- Bell Icon with animation --}}
        <svg class="h-6 w-6 transition-transform duration-200" :class="{ 'animate-wiggle': count > 0 }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        {{-- Badge with pulse animation --}}
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute -top-1 -right-1 flex h-5 min-w-5 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex items-center justify-center h-5 min-w-5 px-1 text-xs font-bold text-white bg-gradient-to-r from-red-500 to-rose-600 rounded-full shadow-lg">
                    {{ auth()->user()->unreadNotifications->count() > 99 ? '99+' : auth()->user()->unreadNotifications->count() }}
                </span>
            </span>
        @endif
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
         class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl ring-1 ring-black/5 z-50 overflow-hidden"
         style="display: none;">
        
        {{-- Header --}}
        <div class="px-5 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifications
                </h3>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.markAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-white/80 hover:text-white transition-colors">
                            Mark all read
                        </button>
                    </form>
                @endif
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <p class="text-sm text-white/70 mt-1">You have {{ auth()->user()->unreadNotifications->count() }} unread notification(s)</p>
            @endif
        </div>
        
        {{-- Notifications List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100">
            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                @php
                    $iconConfig = match($notification->data['category'] ?? 'general') {
                        'expenses' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
                        'activities' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                        'diocese' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                        'contracts' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
                        'evangelism' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-600'],
                        default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    };
                @endphp
                <a href="{{ route('notifications.read', $notification->id) }}" 
                   class="block px-5 py-4 hover:bg-indigo-50/50 transition-all duration-200 group">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 {{ $iconConfig['bg'] }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 {{ $iconConfig['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                {{ $notification->data['message'] ?? 'New Notification' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center">
                    <div class="w-14 h-14 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900">All caught up!</p>
                    <p class="text-xs text-gray-500 mt-1">No new notifications</p>
                </div>
            @endforelse
        </div>
        
        {{-- Footer --}}
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
            <a href="{{ route('notifications.index') }}" 
               class="flex items-center justify-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                View all notifications
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-8deg); }
        75% { transform: rotate(8deg); }
    }
    .animate-wiggle {
        animation: wiggle 0.5s ease-in-out infinite;
        animation-delay: 2s;
        animation-iteration-count: 3;
    }
</style>
