<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <!-- Header Top: Title + Actions -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    Activities
                    @can('view all activities')
                        <span class="px-3 py-1 text-xs font-semibold tracking-wide uppercase bg-purple-100 text-purple-800 rounded-full border border-purple-200">
                            Diocese Overview
                        </span>
                    @elsecan('view assigned activities')
                        <span class="px-3 py-1 text-xs font-semibold tracking-wide uppercase bg-blue-100 text-blue-800 rounded-full border border-blue-200">
                            Archdeaconry View
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold tracking-wide uppercase bg-green-100 text-green-800 rounded-full border border-green-200">
                            Parish View
                        </span>
                    @endcan
                </h1>

                @can('create activities')
                <a href="{{ route('activities.create') }}" class="bg-purple-700 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Activity
                </a>
                @endcan
            </div>

            <!-- Advanced Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('activities.index') }}">
                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- Search -->
                        <div class="col-span-1 md:col-span-2">
                            <label for="search" class="block text-xs font-medium text-gray-700 mb-1">Search Keywords</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    class="focus:ring-purple-500 focus:border-purple-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" 
                                    placeholder="Name, Description...">
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">All Statuses</option>
                                <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <!-- Church Filter (if user has access to see many) -->
                        @if($churches->count() > 1)
                        <div>
                            <label for="church_id" class="block text-xs font-medium text-gray-700 mb-1">Church</label>
                            <select name="church_id" id="church_id" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="">All Churches</option>
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="hidden md:block"></div> <!-- Spacer if no church filter -->
                        @endif

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'status', 'church_id', 'department_id']))
                                <a href="{{ route('activities.index', ['tab' => request('tab')]) }}" class="ml-2 text-sm text-gray-500 hover:text-gray-700 underline">Clear</a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Stats Cards with Filters -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Tabs / Filters -->
                 <div class="md:col-span-4 flex border-b border-gray-200 space-x-8 mb-4">
                    <a href="{{ route('activities.index', ['tab' => 'my_activities']) }}" 
                       class="pb-4 px-1 border-b-2 font-medium text-sm {{ request('tab', 'my_activities') == 'my_activities' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        My Activities
                    </a>
                    <a href="{{ route('activities.index', ['tab' => 'overview']) }}" 
                       class="pb-4 px-1 border-b-2 font-medium text-sm {{ request('tab') == 'overview' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Activities
                    </a>
                    @can('approve activities')
                    <a href="{{ route('activities.index', ['tab' => 'approvals']) }}" 
                       class="pb-4 px-1 border-b-2 font-medium text-sm {{ request('tab') == 'approvals' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Approvals <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs">{{ $stats['pending_approval'] ?? 0 }}</span>
                    </a>
                    @endcan
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                <!-- Activities List -->
                @if($activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-white">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $activity->name }}</h3>
                                            @if($activity->approval_status === 'pending')
                                                <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">Pending Approval</span>
                                            @elseif($activity->approval_status === 'rejected')
                                                 <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-800 border border-red-200">Rejected</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $activity->department->name }} - {{ $activity->church->name }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        @if($activity->status === 'completed')
                                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                                        @elseif($activity->status === 'in_progress')
                                            <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">In Progress</span>
                                        @elseif($activity->status === 'planned')
                                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Planned</span>
                                        @else
                                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress: {{ number_format($activity->current_progress) }} / {{ number_format($activity->target) }}</span>
                                        <span>{{ $activity->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $activity->target > 0 ? min(round(($activity->current_progress / $activity->target) * 100), 100) : 0 }}%"></div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center bg-gray-50 -mx-4 -mb-4 px-4 py-2 mt-4 rounded-b-lg border-t border-gray-100">
                                    <div class="text-xs text-gray-500 flex gap-4">
                                        <span>Start: {{ $activity->start_date->format('M d, Y') }}</span>
                                        @if($activity->end_date)
                                            <span>End: {{ $activity->end_date->format('M d, Y') }}</span>
                                        @endif
                                        @if($activity->budget_estimate > 0)
                                            <span class="text-gray-400">|</span>
                                            <span>Budget: {{ number_format($activity->budget_estimate) }} RWF</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('activities.show', $activity) }}" class="text-purple-600 hover:text-purple-900 text-sm font-medium">View</a>
                                        
                                        @can('edit activities')
                                            <a href="{{ route('activities.edit', $activity) }}" class="text-gray-500 hover:text-gray-700 text-sm">Edit</a>
                                        @endcan

                                        @can('delete activities')
                                            <form action="{{ route('activities.destroy', $activity) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this activity?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $activities->links() }}</div>
                @else
                    <x-empty-state 
                        title="No activities found" 
                        message="Change filters or create a new activity."
                        action="New Activity" 
                        url="{{ route('activities.create') }}"
                        icon="document"
                    />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
