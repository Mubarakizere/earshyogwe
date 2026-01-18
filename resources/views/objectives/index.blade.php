<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                    {{ __('Objectives') }}
                    
                    @can('view all objectives')
                        <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-purple-100 text-purple-700 rounded-full border border-purple-200">
                            Diocese View
                        </span>
                    @elsecan('view assigned objectives')
                        <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-blue-100 text-blue-700 rounded-full border border-blue-200">
                            Archdeaconry
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-green-100 text-green-700 rounded-full border border-green-200">
                            Parish View
                        </span>
                    @endcan
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage and track church objectives and progress.</p>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('objectives.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                </a>

                @can('create objectives')
                <a href="{{ route('objectives.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Objective
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteRoute: '', deleteOpen: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Pro Stats Cards -->
             <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total -->
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-purple-100 text-xs font-bold uppercase tracking-wider">Total Objectives</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</h3>
                        <p class="text-xs text-purple-200 mt-1">All time records</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Pending Approval</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['pending_approval']) }}</h3>
                        <p class="text-xs text-amber-100 mt-1">Awaiting review</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Active -->
                <div class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">In Progress</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['approved']) }}</h3>
                        <p class="text-xs text-blue-100 mt-1">Currently active</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-gradient-to-br from-emerald-400 to-green-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-green-100 text-xs font-bold uppercase tracking-wider">Completed</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['completed']) }}</h3>
                        <p class="text-xs text-green-100 mt-1">Successfully finished</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                @php
                    $isOverview = request('tab') == 'overview';
                    $hasFilters = request()->hasAny(['search', 'status', 'priority', 'church_id', 'department_id']);
                    $showFolders = $isOverview && !$hasFilters;
                @endphp

                <!-- Directorate Folders (Visible only on Overview tab with no filters) -->
                @if($showFolders)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Directorates</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($departments as $dept)
                                <a href="{{ route('objectives.index', ['tab' => 'overview', 'department_id' => $dept->id]) }}" 
                                   class="group bg-white p-6 rounded-lg shadow-sm border border-gray-200 hover:shadow-md hover:border-purple-300 transition-all duration-200 flex flex-col items-center text-center cursor-pointer">
                                    
                                    <!-- Folder Icon -->
                                    <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-100 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                    </div>
                                    
                                    <h4 class="font-bold text-gray-900 group-hover:text-purple-700 transition-colors">{{ $dept->name }}</h4>
                                    
                                    <span class="mt-2 text-xs font-medium bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-full">
                                        View Objectives
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Filters & Tabs -->
            <div class="mb-6 space-y-4">
                 <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="{{ route('objectives.index', ['tab' => 'my_activities']) }}" 
                           class="{{ request('tab', 'my_activities') == 'my_activities' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            My Objectives
                        </a>
                        <a href="{{ route('objectives.index', ['tab' => 'overview']) }}" 
                           class="{{ request('tab') == 'overview' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            All Objectives
                        </a>
                        @can('approve objectives')
                        <a href="{{ route('objectives.index', ['tab' => 'approvals']) }}" 
                           class="{{ request('tab') == 'approvals' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center">
                            Approvals
                            @if($stats['pending_approval'] > 0)
                                <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ $stats['pending_approval'] }}</span>
                            @endif
                        </a>
                        @endcan
                    </nav>
                </div>

                <!-- Advanced Filter Bar (Always visible to allow searching which switches to list view) -->
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <form method="GET" action="{{ route('objectives.index') }}">
                        <input type="hidden" name="tab" value="{{ request('tab', 'my_activities') }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            
                            <!-- Search -->
                            <div class="md:col-span-4">
                                <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                        class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                        placeholder="Objective, Description...">
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="md:col-span-2">
                                <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                <select name="status" id="status" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">All Statuses</option>
                                    <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <!-- Priority -->
                            <div class="md:col-span-2">
                                <label for="priority" class="block text-xs font-medium text-gray-500 mb-1">Priority</label>
                                <select name="priority" id="priority" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">All Priorities</option>
                                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>

                            <!-- Church Filter (Dynamic) -->
                            <div class="md:col-span-3">
                                @if($churches->count() > 1)
                                    <label for="church_id" class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                                    <select name="church_id" id="church_id" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">All Churches</option>
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <!-- Spacer for layout balance -->
                                @endif
                            </div>

                            <!-- Filter & Reset Buttons -->
                            <div class="md:col-span-3 flex space-x-2">
                                <button type="submit" class="flex-1 bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                                    Filter
                                </button>
                                @if(request()->hasAny(['search', 'status', 'church_id', 'department_id', 'start_date']))
                                    <a href="{{ route('objectives.index', ['tab' => request('tab')]) }}" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 inline-flex justify-center text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition" title="Clear Filters">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Objectives Grid/List (Visible when folders NOT shown) -->
            @if(!$showFolders)
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                @if($objectives->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($objectives as $objective)
                            <div class="p-6 hover:bg-gray-50 transition duration-150 ease-in-out relative group">
                                <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                    
                                    {{-- Left: Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                                            {{-- Priority Badge --}}
                                            @if($objective->priority_level)
                                                @if($objective->priority_level === 'critical')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                        🔥 Critical
                                                    </span>
                                                @elseif($objective->priority_level === 'high')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-800 border border-orange-200">
                                                        ⚡ High
                                                    </span>
                                                @elseif($objective->priority_level === 'medium')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        Medium
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                        Low
                                                    </span>
                                                @endif
                                            @endif

                                            {{-- Status Badge --}}
                                            @if($objective->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    Completed
                                                </span>
                                            @elseif($objective->status === 'in_progress')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                    In Progress
                                                </span>
                                            @elseif($objective->status === 'planned')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                    Planned
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                    Cancelled
                                                </span>
                                            @endif

                                            {{-- Approval Badge --}}
                                            @if($objective->approval_status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                                    Pending Approval
                                                </span>
                                            @elseif($objective->approval_status === 'rejected')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-200">
                                                    Rejected
                                                </span>
                                            @endif
                                            
                                            <span class="text-xs text-gray-400">|</span>
                                            <span class="text-xs text-gray-500 font-medium">{{ $objective->department->name }}</span>
                                            
                                            {{-- Category --}}
                                            @if($objective->activity_category)
                                                <span class="text-xs text-gray-400">•</span>
                                                <span class="text-xs text-purple-600 font-medium">{{ $objective->activity_category }}</span>
                                            @endif
                                        </div>

                                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-purple-600 transition truncate">
                                            <a href="{{ route('objectives.show', $objective) }}">{{ $objective->name }}</a>
                                        </h3>
                                        
                                        <div class="flex items-center text-sm text-gray-500 mt-1 gap-4 flex-wrap">
                                            <span class="flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                {{ $objective->church->name }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $objective->start_date->format('M d, Y') }}
                                                @if($objective->duration_days)
                                                    <span class="ml-1 text-xs text-gray-400">({{ $objective->duration_days }} days)</span>
                                                @endif
                                            </span>
                                            {{-- Tracking Frequency --}}
                                            @if($objective->tracking_frequency)
                                                <span class="flex items-center text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded">
                                                    <svg class="flex-shrink-0 mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                                    {{ ucfirst($objective->tracking_frequency) }} tracking
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Middle: Progress --}}
                                    <div class="w-full md:w-1/4 mt-4 md:mt-0">
                                        <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $objective->progress_percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ $objective->progress_percentage }}%"></div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-400 text-right">
                                            Target: {{ number_format($objective->target) }} {{ $objective->target_unit ?? 'units' }}
                                        </div>
                                    </div>

                                    {{-- Right: Actions --}}
                                    <div class="flex items-center md:flex-col justify-end gap-2 mt-4 md:mt-0 md:ml-4">
                                        @can('submit objective reports')
                                        <a href="{{ route('objectives.report.create', $objective) }}" class="text-green-600 hover:text-green-900 text-sm font-medium hover:bg-green-50 px-3 py-1 rounded transition">Report</a>
                                        @endcan
                                        <a href="{{ route('objectives.show', $objective) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium hover:bg-indigo-50 px-3 py-1 rounded transition">View</a>
                                        
                                        @can('edit objectives')
                                        <a href="{{ route('objectives.edit', $objective) }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium hover:bg-gray-100 px-3 py-1 rounded transition">Edit</a>
                                        @endcan

                                        @can('delete objectives')
                                        <button type="button" 
                                            @click="deleteOpen = true; deleteRoute = '{{ route('objectives.destroy', $objective) }}'"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium hover:bg-red-50 px-3 py-1 rounded transition">
                                            Delete
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $objectives->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">No objectives found</h3>
                        <p class="mt-2 text-sm text-gray-500">Get started by creating a new objective or adjusting your filters.</p>
                        <div class="mt-6">
                            @can('create objectives')
                            <a href="{{ route('objectives.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Create Objective
                            </a>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
            @endif
            <!-- Delete Modal -->
            <div x-show="deleteOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="deleteOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteOpen = false"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div x-show="deleteOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Objective</h3>
                                    <div class="mt-2"><p class="text-sm text-gray-500">Are you sure you want to delete this objective? This action cannot be undone.</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <form :action="deleteRoute" method="POST" class="w-full sm:w-auto sm:ml-3">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">Delete</button>
                            </form>
                            <button type="button" @click="deleteOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>