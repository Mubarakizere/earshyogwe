<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">All Notifications</h3>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAsRead') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Mark all as read</button>
                        </form>
                    @endif
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($notifications as $notification)
                        <div class="p-4 flex items-start {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition duration-150">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                    @if(!$notification->read_at)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">New</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->format('M d, Y H:i') }} ({{ $notification->created_at->diffForHumans() }})</p>
                                @if(isset($notification->data['church_name']))
                                    <p class="text-xs text-gray-400 mt-1">From: {{ $notification->data['church_name'] }}</p>
                                @endif
                            </div>
                            <div class="ml-4 flex items-center space-x-2">
                                @if(isset($notification->data['action_url']))
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="text-sm font-medium text-brand-600 hover:text-brand-800">
                                        View
                                    </a>
                                @endif
                                
                                @if(!$notification->read_at)
                                    <a href="{{ route('notifications.read', $notification->id) }}" class="text-gray-400 hover:text-gray-600" title="Mark as read">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            You have no notifications.
                        </div>
                    @endforelse
                </div>
                
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
