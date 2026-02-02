<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                    {{ __('Archdeacon Assignments') }}
                    <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-purple-100 text-purple-700 rounded-full border border-purple-200">
                        Diocese Management
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage archdeacons and their parish assignments.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pro Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Archdeacons -->
                <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-purple-100 text-xs font-bold uppercase tracking-wider">Total Archdeacons</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total_archdeacons']) }}</h3>
                        <p class="text-xs text-purple-200 mt-1">Active leadership</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>
                    </div>
                </div>

                <!-- Total Assignments -->
                <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-cyan-100 text-xs font-bold uppercase tracking-wider">Total Assignments</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total_assignments']) }}</h3>
                        <p class="text-xs text-cyan-200 mt-1">Parish assignments</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" /></svg>
                    </div>
                </div>

                <!-- Unassigned Churches -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Unassigned Parishes</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['unassigned_churches']) }}</h3>
                        <p class="text-xs text-amber-200 mt-1">Need archdeacon</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form method="GET" action="{{ route('archdeacons.index') }}">
                    <div class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="Search by name or email...">
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                Filter
                            </button>
                            @if(request()->filled('search'))
                                <a href="{{ route('archdeacons.index') }}" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 inline-flex justify-center text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition" title="Clear Filters">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Archdeacons List -->
            <div class="space-y-4">
                @forelse ($archdeacons as $archdeacon)
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-100 hover:shadow-xl transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <!-- Archdeacon Info -->
                                <div class="flex items-center flex-1">
                                    <img class="h-16 w-16 rounded-full border-2 border-purple-200 object-cover" src="{{ $archdeacon['profile_photo_url'] }}" alt="{{ $archdeacon['name'] }}">
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $archdeacon['name'] }}</h3>
                                        <p class="text-sm text-gray-500">{{ $archdeacon['email'] }}</p>
                                        <div class="mt-2 flex items-center gap-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-purple-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                Archdeacon
                                            </span>
                                            <span class="text-sm font-semibold text-gray-700">
                                                {{ $archdeacon['churches_count'] }} {{ Str::plural('Parish', $archdeacon['churches_count']) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="ml-4">
                                    <a href="{{ route('archdeacons.edit', $archdeacon['id']) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit Assignments
                                    </a>
                                </div>
                            </div>

                            <!-- Assigned Churches -->
                            @if($archdeacon['churches_count'] > 0)
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Assigned Parishes</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($archdeacon['churches'] as $church)
                                            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                <svg class="mr-1.5 h-3 w-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $church->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <p class="text-sm text-gray-500 italic">No parishes assigned yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 py-12">
                        <div class="text-center">
                            <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No archdeacons found</h3>
                            <p class="mt-1 text-sm text-gray-500">No users with the archdeacon role exist in the system.</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
