<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Evangelism Reports</h2>
            @can('submit evangelism reports')
            <a href="{{ route('evangelism-reports.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Submit Report
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
                <form action="{{ route('evangelism-reports.index') }}" method="GET" class="flex gap-4 items-end">
                    @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                    <div class="w-1/3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Church</label>
                        <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">All Churches</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    
                    <div class="md:w-1/4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div class="md:w-1/4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 px-4 rounded-md shadow transition">
                        Filter
                    </button>
                    <a href="{{ route('evangelism-reports.index') }}" class="text-gray-600 hover:text-gray-900 text-sm font-medium py-2 px-4">Reset</a>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Converts</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_converts ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Baptized</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_baptized ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Confirmed</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_confirmed ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">New Members</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_new_members ?? 0) }}</p>
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
                                    @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    @endif
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Converts</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Baptized</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">New Members</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->report_date->format('M Y') }}</td>
                                        @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $report->church->name }}</td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $report->converts }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $report->baptized }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $report->new_members }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('evangelism-reports.show', $report) }}" class="text-purple-600 hover:text-purple-900 font-medium">View</a>
                                            @if(auth()->id() == $report->submitted_by || auth()->user()->can('view all evangelism'))
                                                <a href="{{ route('evangelism-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $reports->links() }}</div>
                @else
                    <x-empty-state 
                        title="No evangelism reports" 
                        message="Submit your monthly evangelism and discipleship stats."
                        action="Submit Report" 
                        url="{{ route('evangelism-reports.create') }}"
                        icon="document"
                    />
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
