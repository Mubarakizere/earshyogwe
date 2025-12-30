<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Pastor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($church)
                <!-- Welcome Section -->
                <div class="bg-gradient-to-r from-green-600 to-teal-700 rounded-lg shadow-lg p-8 mb-8 text-white">
                    <h3 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-green-100">Pastor of {{ $church->name }}</p>
                </div>

                <!-- Church Info Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Church Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-4">{{ $church->name }}</h4>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Location</p>
                                            <p class="text-gray-900">{{ $church->location }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($church->address)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Address</p>
                                            <p class="text-gray-900">{{ $church->address }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($church->phone)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Phone</p>
                                            <p class="text-gray-900">{{ $church->phone }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Diocese</p>
                                            <p class="text-gray-900">{{ $church->diocese ?? 'Not Set' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Region</p>
                                            <p class="text-gray-900">{{ $church->region ?? 'Not Set' }}</p>
                                        </div>
                                    </div>

                                    @if($church->archid)
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 mr-3 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-500">Supervisor (Archid)</p>
                                            <p class="text-gray-900">{{ $church->archid->name }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Departments</dt>
                                        <dd class="text-3xl font-bold text-gray-900">{{ $stats['departments'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Active Departments</dt>
                                        <dd class="text-3xl font-bold text-gray-900">{{ $stats['active_departments'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Departments List -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Church Departments</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($church->departments as $department)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $department->name }}</h4>
                                    @if($department->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </div>
                                @if($department->description)
                                <p class="text-sm text-gray-600">{{ $department->description }}</p>
                                @endif
                            </div>
                            @empty
                            <div class="col-span-3 text-center py-8">
                                <p class="text-gray-500">No departments created yet</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <!-- No Church Assigned -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-yellow-800">No Church Assigned</h3>
                            <p class="mt-2 text-yellow-700">{{ $message ?? 'You are not assigned to any church yet. Please contact your administrator.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
