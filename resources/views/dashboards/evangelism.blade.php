<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Evangelism Dashboard (Diocese)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-indigo-600 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-sm font-medium opacity-80">Total New Converts</p>
                    <p class="text-4xl font-bold">{{ number_format($totalConverts) }}</p>
                </div>
                
                <div class="bg-blue-500 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-sm font-medium opacity-80">Total Baptized</p>
                    <p class="text-4xl font-bold">{{ number_format($totalBaptized) }}</p>
                </div>

                <div class="bg-purple-500 rounded-lg p-6 text-white shadow-lg">
                    <p class="text-sm font-medium opacity-80">Total New Members</p>
                    <p class="text-4xl font-bold">{{ number_format($totalNewMembers) }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Evangelism Reports</h3>
                     {{-- <a href="{{ route('evangelism.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a> --}}
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Report Month</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentReports as $report)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $report->church->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($report->report_date)->format('F Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $report->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No reports recently</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
