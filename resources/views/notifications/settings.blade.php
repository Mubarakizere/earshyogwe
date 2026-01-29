<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('notifications.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ __('Notification Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('notifications.updateSettings') }}" method="POST">
                @csrf

                {{-- Global Channels --}}
                <div class="bg-white rounded-lg border border-gray-200 mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Notification Channels</h3>
                        <p class="text-sm text-gray-500">Choose how you want to receive notifications</p>
                    </div>
                    <div class="p-4 space-y-4">
                        {{-- Email --}}
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Email</p>
                                    <p class="text-sm text-gray-500">Get notifications in your inbox</p>
                                </div>
                            </div>
                            <input type="checkbox" name="email_enabled" value="1" {{ $preferences['email'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </label>

                        {{-- Push --}}
                        <label class="flex items-center justify-between p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Push Notifications</p>
                                    <p class="text-sm text-gray-500">Get alerts on your phone & computer</p>
                                </div>
                            </div>
                            <input type="checkbox" name="push_enabled" value="1" {{ $preferences['push'] ? 'checked' : '' }}
                                   class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        </label>
                    </div>
                </div>

                {{-- Category Settings --}}
                <div class="bg-white rounded-lg border border-gray-200 mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-base font-semibold text-gray-900">Category Preferences</h3>
                        <p class="text-sm text-gray-500">Control notifications for each category</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($categories as $key => $category)
                            @php
                                $channelPrefs = $preferences['channels'][$key] ?? ['email' => true, 'push' => true];
                            @endphp
                            <div class="p-4 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">{{ $category['icon'] }}</span>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $category['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $category['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                                        <input type="checkbox" name="channels[{{ $key }}][email]" value="1" 
                                               {{ $channelPrefs['email'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-600">Email</span>
                                    </label>
                                    <label class="flex items-center gap-1.5 text-sm cursor-pointer">
                                        <input type="checkbox" name="channels[{{ $key }}][push]" value="1" 
                                               {{ $channelPrefs['push'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-600">Push</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Save --}}
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800">
                        Save Settings
                    </button>
                </div>
            </form>

            {{-- Push Permission Section --}}
            <div class="mt-8 bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">Browser Push Notifications</h4>
                        <p class="text-sm text-gray-500 mb-3">Allow push notifications to receive alerts even when the app is closed.</p>
                        <div id="push-container">
                            <button type="button" id="enable-push-btn" 
                                    class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                                Enable Push Notifications
                            </button>
                            <p id="push-status" class="text-sm text-gray-500 mt-2"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Push notification script loaded');
            const btn = document.getElementById('enable-push-btn');
            const statusEl = document.getElementById('push-status');
            
            // Check status on load
            window.OneSignalDeferred = window.OneSignalDeferred || [];
            window.OneSignalDeferred.push(function(OneSignal) {
                console.log('OneSignal initialized', OneSignal);
                checkOneSignalStatus(OneSignal);
            });
            
            async function checkOneSignalStatus(OneSignal) {
                try {
                    console.log('Checking OneSignal status...');
                    const permission = OneSignal.Notifications.permission;
                    const subscribed = OneSignal.User.PushSubscription.optedIn;
                    console.log('Permission:', permission);
                    console.log('Subscribed:', subscribed);
                    
                    if (permission && subscribed) {
                        statusEl.textContent = '✓ Push notifications are enabled';
                        statusEl.className = 'text-sm text-green-600 mt-2';
                        btn.textContent = 'Enabled';
                        btn.disabled = true;
                        btn.className = 'px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed';
                    } else if (permission === 'denied' || Notification.permission === 'denied') {
                        statusEl.textContent = 'Push notifications are blocked. Please enable them in your browser settings.';
                        statusEl.className = 'text-sm text-red-600 mt-2';
                        btn.disabled = true;
                        btn.className = 'px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed';
                    } else {
                        console.log('Push not fully enabled yet');
                    }
                } catch (e) {
                    console.error('Error checking status:', e);
                }
            }
            
            btn.addEventListener('click', async function() {
                console.log('Enable button clicked');
                statusEl.textContent = 'Requesting permission...';
                
                window.OneSignalDeferred.push(async function(OneSignal) {
                    try {
                        console.log('Requesting permission via OneSignal...');
                        const result = await OneSignal.Notifications.requestPermission();
                        console.log('Permission result:', result);
                        
                        if (result) {
                            // Wait a moment for subscription to update
                            console.log('Permission granted, checking subscription...');
                            statusEl.textContent = '✓ Push notifications enabled successfully!';
                            statusEl.className = 'text-sm text-green-600 mt-2';
                            
                            btn.textContent = 'Enabled';
                            btn.disabled = true;
                            btn.className = 'px-4 py-2 bg-gray-400 text-white text-sm font-medium rounded-lg cursor-not-allowed';
                            
                            // Log in with user ID for targeted notifications
                            @auth
                            console.log('Logging in user: {{ auth()->id() }}');
                            await OneSignal.login("{{ auth()->id() }}");
                            @endauth
                        } else {
                            console.log('Permission denied/dismissed');
                            statusEl.textContent = 'Permission not granted. Please try again.';
                            statusEl.className = 'text-sm text-yellow-600 mt-2';
                        }
                    } catch (error) {
                        console.error('Push subscription error:', error);
                        statusEl.textContent = 'Error: ' + error.message;
                        statusEl.className = 'text-sm text-red-600 mt-2';
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
