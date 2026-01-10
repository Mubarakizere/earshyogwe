<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
             <!-- Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('Evangelism Reports') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">Track soul-winning, baptisms, and church growth efforts.</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                     @can('submit evangelism reports')
                        <span class="inline-flex rounded-md shadow-sm">
                            <a href="{{ route('evangelism-reports.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors shadow-sm">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Submit Report
                            </a>
                        </span>
                    @endcan
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <!-- Total Converts -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="relative flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-100 uppercase tracking-wider">Total Converts</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($totals->total_converts ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Baptized -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="relative flex items-center justify-between">
                         <div>
                            <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">Baptized</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($totals->total_baptized ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Confirmed -->
                 <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="relative flex items-center justify-between">
                         <div>
                            <p class="text-sm font-medium text-green-100 uppercase tracking-wider">Confirmed</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($totals->total_confirmed ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- New Members -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-500 to-orange-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="relative flex items-center justify-between">
                         <div>
                            <p class="text-sm font-medium text-yellow-100 uppercase tracking-wider">New Members</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($totals->total_new_members ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filter Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <form action="{{ route('evangelism-reports.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Church</label>
                        <select name="church_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm rounded-lg">
                            <option value="">All Churches</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <!-- Spacer if no church filter -->
                    <div class="hidden md:block md:col-span-3"></div>
                    @endif
                    
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-lg">
                    </div>
                    
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-lg">
                    </div>

                    <div class="md:col-span-3 flex justify-end space-x-3">
                        <a href="{{ route('evangelism-reports.index') }}" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Reset
                        </a>
                        <button type="submit" class="bg-gray-900 text-white hover:bg-gray-800 px-6 py-2 rounded-lg text-sm font-medium shadow-md transition-colors">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reports Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-50">
                 @if($reports->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                             <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Report Month</th>
                                    @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church</th>
                                    @endif
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Converts</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Baptized</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">New Members</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @foreach($reports as $report)
                                    <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                         <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ $report->report_date->format('F Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $report->report_date->format('d M Y') }}</div>
                                        </td>
                                        @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                                {{ $report->church->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $report->converts }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $report->baptized }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $report->new_members }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a href="{{ route('evangelism-reports.show', $report) }}" class="text-gray-500 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition-colors">
                                                    View
                                                </a>
                                                @if(auth()->id() == $report->submitted_by || auth()->user()->can('view all evangelism'))
                                                    <a href="{{ route('evangelism-reports.edit', $report) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                                        Edit
                                                    </a>
                                                    
                                                    <!-- Delete Button & Modal -->
                                                    <button type="button" 
                                                        x-data=""
                                                        x-on:click="$dispatch('open-modal', 'delete-report-{{ $report->id }}')"
                                                        class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors">
                                                        Delete
                                                    </button>
                                                    
                                                    <x-modal name="delete-report-{{ $report->id }}" :show="false" focusable>
                                                        <form method="POST" action="{{ route('evangelism-reports.destroy', $report) }}" class="p-6">
                                                            @csrf
                                                            @method('DELETE')

                                                            <h2 class="text-lg font-medium text-gray-900">
                                                                {{ __('Delete Evangelism Report?') }}
                                                            </h2>

                                                            <p class="mt-1 text-sm text-gray-600">
                                                                Are you sure you want to delete this report for <span class="font-bold">{{ $report->report_date->format('F Y') }}</span>? This action cannot be undone.
                                                            </p>

                                                            <div class="mt-6 flex justify-end space-x-3">
                                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                                    {{ __('Cancel') }}
                                                                </x-secondary-button>

                                                                <x-danger-button class="ml-3">
                                                                    {{ __('Delete Report') }}
                                                                </x-danger-button>
                                                            </div>
                                                        </form>
                                                    </x-modal>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                     <div class="flex items-center justify-between border-t border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
                        {{ $reports->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">No reports found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new evangelism report.</p>
                        @can('submit evangelism reports')
                        <div class="mt-6">
                             <a href="{{ route('evangelism-reports.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Create Report
                            </a>
                        </div>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
