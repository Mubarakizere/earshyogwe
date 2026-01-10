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
                <a href="{{ route('workers.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                </a>

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
                        <p class="text-xs text-teal-100 mt-1">Across all departments</p>
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

            <!-- Advanced Filter Bar -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                <form method="GET" action="{{ route('workers.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                        
                        <!-- Search -->
                        <div class="md:col-span-4">
                            <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="focus:ring-teal-500 focus:border-teal-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="Name, position, or phone...">
                            </div>
                        </div>

                        <!-- Church Filter (Dynamic) -->
                        <div class="md:col-span-3">
                            @if($churches->count() > 1)
                                <label for="church_id" class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                                <select name="church_id" id="church_id" class="focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">All Churches</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <label class="block text-xs font-medium text-gray-400 mb-1">Church</label>
                                <input type="text" disabled value="{{ $churches->first()->name ?? 'N/A' }}" class="block w-full sm:text-sm border-gray-200 bg-gray-50 rounded-md text-gray-500">
                            @endif
                        </div>

                         <!-- Department Filter -->
                         <div class="md:col-span-2">
                             <label for="department_id" class="block text-xs font-medium text-gray-500 mb-1">Department</label>
                             <select name="department_id" id="department_id" class="focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                 <option value="">All Depts</option>
                                 @foreach($departments as $dept)
                                     <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                 @endforeach
                             </select>
                         </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                             <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                             <select name="status" id="status" class="focus:ring-teal-500 focus:border-teal-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                 <option value="">All Statuses</option>
                                 <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                 <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                                 <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                             </select>
                        </div>
                        
                        <!-- Filter Buttons -->
                        <div class="md:col-span-1 flex space-x-2">
                            <button type="submit" class="w-full bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-2 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </button>
                            @if(request()->hasAny(['search', 'church_id', 'department_id', 'status']))
                                <a href="{{ route('workers.index') }}" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-2 inline-flex justify-center text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition" title="Clear Filters">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position & Dept</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organization</th>
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
                                            <div class="text-sm font-medium text-gray-900">{{ $worker->position }}</div>
                                            <div class="text-xs text-gray-500">{{ $worker->department->name ?? 'No Dept' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                             <div class="text-xs text-gray-500">{{ $worker->church->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($worker->status === 'active')
                                                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                                <!-- Retirement Alerts -->
                                                @if($worker->retirement_status === 'overdue')
                                                     <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                                        Overdue: {{ $worker->years_overdue }}y
                                                     </span>
                                                @elseif($worker->retirement_status === 'soon')
                                                     <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                        Retiring in {{ $worker->years_to_retirement }}y
                                                     </span>
                                                @endif
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
                        <p class="mt-1 text-sm text-gray-500">Get started by adding a new worker.</p>
                        <div class="mt-6">
                            @can('create worker')
                            <a href="{{ route('workers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Worker
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
