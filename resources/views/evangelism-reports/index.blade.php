<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Evangelism Reports</h2>
            <a href="{{ route('evangelism-reports.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Submit Report
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

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Converts</p>
                    <p class="text-3xl font-bold mt-2">{{ $totals->total_converts ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Baptized</p>
                    <p class="text-3xl font-bold mt-2">{{ $totals->total_baptized ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Confirmed</p>
                    <p class="text-3xl font-bold mt-2">{{ $totals->total_confirmed ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">New Members</p>
                    <p class="text-3xl font-bold mt-2">{{ $totals->total_new_members ?? 0 }}</p>
                </div>
            </div>

            <!-- Reports Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                @if($reports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Converts</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Baptized</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Members</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $report->report_date->format('M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $report->church->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $report->converts }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $report->baptized }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $report->new_members }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('evangelism-reports.show', $report) }}" class="text-purple-600 hover:text-purple-900">View</a>
                                            <a href="{{ route('evangelism-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $reports->links() }}</div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No evangelism reports yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
