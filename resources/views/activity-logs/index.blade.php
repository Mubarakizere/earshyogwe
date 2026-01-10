<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-3">
            {{ __('App Usage & Activity') }}
            <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-indigo-100 text-indigo-700 rounded-full border border-indigo-200">
                Audit Log
            </span>
        </h2>
        <p class="text-sm text-gray-500 mt-1">Monitor system usage and audit user actions.</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pro Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Logs -->
                <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Total Actions</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total_logs']) }}</h3>
                        <p class="text-xs text-indigo-100 mt-1">Logged system-wide</p>
                    </div>
                </div>

                <!-- Today's Activity -->
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Today's Activity</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['today_logs']) }}</h3>
                        <p class="text-xs text-emerald-100 mt-1">Records created today</p>
                    </div>
                </div>

                <!-- Top Contributor -->
                <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-violet-100 text-xs font-bold uppercase tracking-wider">Top Contributor</p>
                        @if($stats['top_user'])
                            <div class="flex items-center mt-2">
                                <img class="h-10 w-10 rounded-full border-2 border-white/30" src="{{ $stats['top_user']->profile_photo_url }}" alt="{{ $stats['top_user']->name }}">
                                <div class="ml-3">
                                    <h3 class="text-lg font-bold">{{ $stats['top_user']->name }}</h3>
                                    <p class="text-xs text-violet-100">{{ number_format($stats['top_user_count'] ?? 0) }} actions</p>
                                </div>
                            </div>
                        @else
                            <h3 class="text-xl font-bold mt-1">--</h3>
                            <p class="text-xs text-violet-100 mt-1">No activity recorded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Advanced Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6 p-5 border border-gray-200">
                <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4 items-end">
                    
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Search Description</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Deleted Invoice..." 
                               class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    </div>

                    <!-- Module Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Module</label>
                        <select name="module" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                        <select name="user_id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Start Date -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-4 lg:col-span-6 flex justify-end gap-2 mt-2 border-t pt-4">
                        @if(request()->hasAny(['search', 'module', 'user_id', 'start_date', 'end_date']))
                            <a href="{{ route('activity-logs.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                Clear Filters
                            </a>
                        @endif
                        <button type="submit" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md text-sm font-medium text-white hover:bg-gray-900 transition shadow-sm">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Pro Logs Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($log->user)
                                                <img class="h-8 w-8 rounded-full object-cover mr-3 border border-gray-200" src="{{ $log->user->profile_photo_url }}" alt="{{ $log->user->name }}">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-bold text-gray-900">{{ $log->user->name }}</span>
                                                    <span class="text-xs text-gray-400">{{ $log->ip_address }}</span>
                                                </div>
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                </div>
                                                <span class="text-sm text-gray-500 italic">System</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $action = strtolower($log->action);
                                            $badgeClass = match($action) {
                                                'created' => 'bg-green-100 text-green-800',
                                                'updated' => 'bg-blue-100 text-blue-800',
                                                'deleted' => 'bg-red-100 text-red-800',
                                                'login' => 'bg-purple-100 text-purple-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-bold rounded-full uppercase {{ $badgeClass }}">
                                            {{ $action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 max-w-md truncate" title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-50 text-gray-600 border border-gray-200">
                                            {{ ucfirst($log->module) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
