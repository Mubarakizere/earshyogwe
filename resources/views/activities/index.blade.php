<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Activities</h2>
            <a href="{{ route('activities.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg">
                + New Activity
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Activities</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">In Progress</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Completed</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['completed'] }}</p>
                </div>
            </div>

            <!-- Activities List -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                @if($activities->count() > 0)
                    <div class="space-y-4">
                        @foreach($activities as $activity)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $activity->name }}</h3>
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
                                        <span>Progress: {{ $activity->current_progress }} / {{ $activity->target }}</span>
                                        <span>{{ $activity->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $activity->progress_percentage }}%"></div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500">
                                        <span>Start: {{ $activity->start_date->format('M d, Y') }}</span>
                                        @if($activity->end_date)
                                            <span class="ml-3">End: {{ $activity->end_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                    <div class="space-x-2">
                                        <a href="{{ route('activities.show', $activity) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a>
                                        <a href="{{ route('activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">{{ $activities->links() }}</div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No activities yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
