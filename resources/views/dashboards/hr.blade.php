<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('HR Dashboard (Diocese)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($expiringContracts > 0)
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                You have <span class="font-bold">{{ $expiringContracts }}</span> contracts expiring within the next 30 days.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">Total Workers</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalWorkers }}</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">Expiring Contracts</p>
                    <p class="text-3xl font-bold text-red-600">{{ $expiringContracts }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Hires</h3>
                    {{-- Assuming route exists --}}
                    {{-- <a href="{{ route('workers.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a> --}}
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentHires as $worker)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $worker->first_name }} {{ $worker->last_name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $worker->position }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $worker->department->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $worker->employment_date ? \Carbon\Carbon::parse($worker->employment_date)->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-4 py-3 text-sm text-gray-500 text-center">No recent hires</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
