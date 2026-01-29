<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('notifications.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-900 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </span>
                {{ __('Notification Settings') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('notifications.updateSettings') }}" method="POST">
                @csrf

                {{-- Global Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Global Notification Channels
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Control which channels can deliver notifications</p>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Email Toggle --}}
                        <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Email Notifications</p>
                                    <p class="text-sm text-gray-500">Receive notifications via email</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="email_enabled" value="1" {{ $preferences['email'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-8 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-6"></div>
                            </div>
                        </label>

                        {{-- Push Toggle --}}
                        <label class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Push Notifications</p>
                                    <p class="text-sm text-gray-500">Receive push notifications on your phone & PC</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" name="push_enabled" value="1" {{ $preferences['push'] ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-14 h-8 bg-gray-200 rounded-full peer-checked:bg-indigo-600 transition-colors"></div>
                                <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-6"></div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Per-Category Settings --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Category Preferences
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Fine-tune notifications for each category</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($categories as $key => $category)
                            @php
                                $channelPrefs = $preferences['channels'][$key] ?? ['email' => true, 'push' => true];
                            @endphp
                            <div class="p-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="text-2xl">{{ $category['icon'] }}</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $category['name'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $category['description'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-6 ml-12">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="channels[{{ $key }}][email]" value="1" 
                                               {{ $channelPrefs['email'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">Email</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="channels[{{ $key }}][push]" value="1" 
                                               {{ $channelPrefs['push'] ? 'checked' : '' }}
                                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-sm text-gray-700">Push</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition-all duration-200 transform hover:scale-[1.02]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Preferences
                    </button>
                </div>
            </form>

            {{-- OneSignal Push Setup Section --}}
            <div class="mt-8 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border border-purple-100 p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">Enable Push Notifications</h4>
                        <p class="text-sm text-gray-600 mb-4">Get instant notifications on your phone and computer even when the app is closed.</p>
                        <button type="button" id="enable-push-btn" onclick="enablePushNotifications()" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            Enable Push Notifications
                        </button>
                        <p id="push-status" class="text-xs text-gray-500 mt-2"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function enablePushNotifications() {
            // This will be connected to OneSignal SDK
            const statusEl = document.getElementById('push-status');
            statusEl.textContent = 'Checking browser support...';
            
            if (!('Notification' in window)) {
                statusEl.textContent = '❌ Your browser does not support push notifications.';
                return;
            }
            
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    statusEl.textContent = '✅ Push notifications enabled! You will now receive notifications.';
                    document.getElementById('enable-push-btn').textContent = 'Push Enabled ✓';
                    document.getElementById('enable-push-btn').disabled = true;
                    document.getElementById('enable-push-btn').classList.add('opacity-50');
                } else {
                    statusEl.textContent = '❌ Permission denied. Please enable notifications in your browser settings.';
                }
            });
        }
        
        // Check current permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusEl = document.getElementById('push-status');
            const btn = document.getElementById('enable-push-btn');
            
            if (Notification.permission === 'granted') {
                statusEl.textContent = '✅ Push notifications are enabled.';
                btn.textContent = 'Push Enabled ✓';
                btn.disabled = true;
                btn.classList.add('opacity-50');
            } else if (Notification.permission === 'denied') {
                statusEl.textContent = '❌ Push notifications are blocked. Enable them in browser settings.';
            }
        });
    </script>
    @endpush
</x-app-layout>
