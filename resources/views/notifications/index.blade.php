<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Notifications') }}
            </h2>
            <a href="{{ route('notifications.settings') }}" class="text-sm text-gray-600 hover:text-gray-900 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Stats Row --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Unread</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['unread'] }}</p>
                </div>
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today'] }}</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-white rounded-lg border border-gray-200 mb-4">
                <div class="p-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-1">
                        <a href="{{ route('notifications.index') }}" 
                           class="px-3 py-1.5 rounded text-sm {{ !$filter || $filter === 'all' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            All
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                           class="px-3 py-1.5 rounded text-sm {{ $filter === 'unread' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            Unread
                        </a>
                        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                           class="px-3 py-1.5 rounded text-sm {{ $filter === 'read' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            Read
                        </a>
                    </div>

                    <div class="flex items-center gap-2">
                        <select onchange="window.location.href=this.value" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            <option value="{{ route('notifications.index', ['filter' => $filter]) }}">All Categories</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ route('notifications.index', ['filter' => $filter, 'category' => $key]) }}" {{ $category === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @if($stats['unread'] > 0)
                            <form action="{{ route('notifications.markAsRead') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 whitespace-nowrap">
                                    Mark all read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Notifications List --}}
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                @forelse($notifications as $notification)
                    <div class="border-b border-gray-100 last:border-b-0 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                        <div class="p-4 flex items-start gap-3">
                            {{-- Category Badge --}}
                            @php
                                $cat = $notification->data['category'] ?? 'general';
                                $catColors = [
                                    'expenses' => 'bg-yellow-100 text-yellow-700',
                                    'activities' => 'bg-blue-100 text-blue-700',
                                    'diocese' => 'bg-purple-100 text-purple-700',
                                    'contracts' => 'bg-green-100 text-green-700',
                                    'evangelism' => 'bg-pink-100 text-pink-700',
                                ];
                                $catColor = $catColors[$cat] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="flex-shrink-0 px-2 py-1 text-xs font-medium rounded {{ $catColor }}">
                                {{ ucfirst($cat) }}
                            </span>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 {{ $notification->read_at ? '' : 'font-medium' }}">
                                    {{ $notification->data['message'] ?? 'New notification' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ route('notifications.read', $notification->id) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-gray-500">No notifications yet</p>
                    </div>
                @endforelse
                
                @if($notifications->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $notifications->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            {{-- Clear Read --}}
            @if($stats['total'] - $stats['unread'] > 0)
                <div class="mt-4 text-center">
                    <form action="{{ route('notifications.destroyRead') }}" method="POST" onsubmit="return confirm('Delete all read notifications?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Clear all read notifications
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
