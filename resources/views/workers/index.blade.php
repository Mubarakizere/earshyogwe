<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight flex items-center gap-3">
                    {{ __('Workers') }}
                    <span class="px-3 py-1 text-xs font-bold tracking-wider uppercase bg-teal-100 text-teal-700 rounded-full border border-teal-200">
                        HR Management
                    </span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Manage employed staff across the diocese network.</p>
            </div>
            
            <div class="flex gap-2">
                @can('delete worker')
                    <a href="{{ route('workers.trashed') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        View Deleted
                    </a>
                @endcan
                
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="{{ route('workers.export', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Export as CSV (Excel)
                            </a>
                            <a href="{{ route('workers.exportPdf', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Export as PDF
                            </a>
                        </div>
                    </div>
                </div>

                @can('create worker')
                <a href="{{ route('workers.create') }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-700 focus:bg-teal-700 active:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Worker
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteRoute: '', deleteOpen: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pro Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total -->
                <div class="bg-gradient-to-br from-teal-500 to-emerald-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-teal-100 text-xs font-bold uppercase tracking-wider">Total Workers</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['total']) }}</h3>
                        <p class="text-xs text-teal-100 mt-1">Across all institutions</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Retiring Soon -->
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-amber-100 text-xs font-bold uppercase tracking-wider">Retiring Soon</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['retiring_soon']) }}</h3>
                        <p class="text-xs text-amber-100 mt-1">Within next 2 years</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                         <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Overdue -->
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-lg shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-red-100 text-xs font-bold uppercase tracking-wider">Overdue</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stats['overdue']) }}</h3>
                        <p class="text-xs text-red-100 mt-1">Past retirement age</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                         <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>


            <!-- Search and Filters Section -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-100">
                <form method="GET" action="{{ route('workers.index') }}">
                    <!-- Enhanced Search Bar -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Search Workers</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search by name, job title, email, or phone..." 
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition duration-150 text-sm"
                            >
                        </div>
                    </div>

                    <!-- Institution Filters -->
                    <div class="space-y-4">
                        <!-- Filter by Institution Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Filter by Institution Type</label>
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    type="submit" 
                                    name="institution_type" 
                                    value="" 
                                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ !request('institution_type') && !request('institution_id') ? 'bg-teal-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    All Institutions
                                    <span class="ml-1 text-xs opacity-75">({{ $stats['total'] }})</span>
                                </button>
                                @foreach($institutionsByType as $type => $typeInstitutions)
                                    <button 
                                        type="submit"
                                        name="institution_type" 
                                        value="{{ $type }}"
                                        onclick="document.querySelector('input[name=institution_id]').value = '';"
                                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('institution_type') === $type ? 'bg-teal-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        {{ $typeLabels[$type] ?? ucwords(str_replace('_', ' ', $type)) }}
                                        <span class="ml-1 text-xs opacity-75">({{ $typeCounts[$type] ?? 0 }})</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Individual Institutions (Show based on selected type or all) -->
                        @php
                            $selectedType = request('institution_type');
                            $displayInstitutions = $selectedType && isset($institutionsByType[$selectedType]) 
                                ? $institutionsByType[$selectedType] 
                                : [];
                        @endphp

                        @if(count($displayInstitutions) > 0 || !request('institution_type'))
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    {{ request('institution_type') ? 'Select ' . ($typeLabels[request('institution_type')] ?? 'Institution') : 'Select Specific Institution' }}
                                </label>
                                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-2 bg-gray-50 rounded-lg border border-gray-200">
                                    @if(!request('institution_type'))
                                        <p class="text-xs text-gray-500 italic w-full">Select an institution type above to see specific institutions</p>
                                    @else
                                        @foreach($displayInstitutions as $institution)
                                            <button 
                                                type="submit"
                                                name="institution_id" 
                                                value="{{ $institution->id }}"
                                                class="px-3 py-1.5 rounded-md font-medium text-xs transition-all duration-200 {{ request('institution_id') == $institution->id ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-indigo-50 border border-gray-300' }}">
                                                {{ $institution->name }}
                                                <span class="ml-1 opacity-75">({{ $institutionStats[$institution->id] ?? 0 }})</span>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Filter by Status</label>
                            <div class="flex flex-wrap gap-2">
                                <button 
                                    type="submit" 
                                    name="status" 
                                    value="" 
                                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ !request('status') ? 'bg-gray-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    All Status
                                </button>
                                <button 
                                    type="submit" 
                                    name="status" 
                                    value="active" 
                                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('status') === 'active' ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Active
                                </button>
                                <button 
                                    type="submit" 
                                    name="status" 
                                    value="retired" 
                                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('status') === 'retired' ? 'bg-gray-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Retired
                                </button>
                                <button 
                                    type="submit" 
                                    name="status" 
                                    value="terminated" 
                                    class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 {{ request('status') === 'terminated' ? 'bg-red-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    Terminated
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs to preserve certain filters -->
                    @if(request('search'))
                        <input type="hidden" name="search_preserve" value="{{ request('search') }}">
                    @endif
                    @if(request('institution_type'))
                        <input type="hidden" name="institution_type_preserve" value="{{ request('institution_type') }}">
                    @endif

                    <!-- Clear Filters Button -->
                    @if(request()->hasAny(['search', 'institution_id', 'institution_type', 'status']))
                        <div class="flex justify-end mt-6">
                            <a href="{{ route('workers.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Enhanced Table List -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                @if($workers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Worker Profile</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($workers as $worker)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                 <div class="flex-shrink-0 h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 uppercase font-bold text-sm">
                                                    {{ substr($worker->first_name, 0, 1) }}{{ substr($worker->last_name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                     <a href="{{ route('workers.show', $worker) }}" class="text-sm font-bold text-gray-900 hover:text-teal-600 transition">
                                                        {{ $worker->full_name }}
                                                    </a>
                                                    <div class="text-xs text-gray-500">{{ $worker->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $worker->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                {{ ucfirst($worker->gender ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $worker->job_title ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">Emp: {{ $worker->employment_date?->format('M Y') ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($worker->institution)
                                                <div class="text-sm text-gray-900">{{ $worker->institution->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $worker->institution->type_name }}</div>
                                            @else
                                                <span class="text-xs text-gray-400">No Institution</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($worker->status === 'active')
                                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @elseif($worker->status === 'retired')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Retired
                                                </span>
                                            @else
                                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Terminated
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('workers.show', $worker) }}" class="text-teal-600 hover:text-teal-900 font-medium">View</a>
                                            
                                            @can('edit worker')
                                                <a href="{{ route('workers.edit', $worker) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                            @endcan
                                            
                                            @can('delete worker')
                                                <span class="text-gray-300">|</span>
                                                <button 
                                                    type="button" 
                                                    @click="deleteOpen = true; deleteRoute = '{{ route('workers.destroy', $worker) }}'"
                                                    class="text-red-500 hover:text-red-700 font-medium">
                                                    Delete
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $workers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                         <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No workers found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by adding a new worker or check deleted workers.</p>
                        <div class="mt-6 flex justify-center gap-3">
                            @can('create worker')
                            <a href="{{ route('workers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Worker
                            </a>
                            @endcan
                            
                            @can('delete worker')
                            <a href="{{ route('workers.trashed') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                View Deleted Workers
                            </a>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>

            <!-- Delete Confirmation Modal -->
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
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Worker</h3>
                                    <div class="mt-2"><p class="text-sm text-gray-500">Are you sure you want to delete this worker? This action cannot be undone.</p></div>
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
