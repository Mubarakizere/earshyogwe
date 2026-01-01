<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">HR Management - Workers</h2>
            <a href="{{ route('workers.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Add Worker
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Active Workers</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Retiring Soon (2 years)</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['retiring_soon'] }}</p>
                </div>
            </div>

            <!-- Workers Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                @if($workers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employment Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($workers as $worker)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $worker->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $worker->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $worker->position }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $worker->church->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $worker->employment_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($worker->status === 'active')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                            @elseif($worker->status === 'retired')
                                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Retired</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Terminated</span>
                                            @endif
                                            @if($worker->years_to_retirement !== null && $worker->years_to_retirement <= 2 && $worker->years_to_retirement > 0)
                                                <span class="ml-1 px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Retiring Soon</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('workers.show', $worker) }}" class="text-teal-600 hover:text-teal-900">View</a>
                                            <a href="{{ route('workers.edit', $worker) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $workers->links() }}</div>
                @else
                    <x-empty-state 
                        title="No workers found" 
                        message="Get started by adding your first worker or contract."
                        action="Add Worker" 
                        url="{{ route('workers.create') }}"
                        icon="users"
                    />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
