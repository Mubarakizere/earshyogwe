<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Attendance Management</h2>
            <a href="{{ route('attendances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Record Attendance
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

            <!-- Filter Bar -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form action="{{ route('attendances.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Service Type -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Service Type</label>
                        <select name="service_type" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Services</option>
                            <option value="sunday_service" {{ request('service_type') == 'sunday_service' ? 'selected' : '' }}>Sunday Service</option>
                            <option value="prayer_meeting" {{ request('service_type') == 'prayer_meeting' ? 'selected' : '' }}>Prayer Meeting</option>
                            <option value="bible_study" {{ request('service_type') == 'bible_study' ? 'selected' : '' }}>Bible Study</option>
                            <option value="youth_service" {{ request('service_type') == 'youth_service' ? 'selected' : '' }}>Youth Service</option>
                            <option value="special_event" {{ request('service_type') == 'special_event' ? 'selected' : '' }}>Special Event</option>
                            <option value="other" {{ request('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Church Filter (if user has access to multiple) -->
                    @if(auth()->user()->hasRole('boss') || auth()->user()->hasRole('archid'))
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                        <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Churches</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <!-- Spacer for alignment if not admin -->
                        <div class="hidden md:block"></div> 
                    @endif

                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md shadow-sm">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ route('attendances.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-4 rounded-md shadow-sm">Reset</a>
                    </div>
                </form>

                <!-- Export & Print Options -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('attendances.export', request()->query()) }}" class="flex items-center text-green-700 bg-green-100 hover:bg-green-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export CSV
                    </a>
                    <button onclick="window.print()" class="flex items-center text-gray-700 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print View
                    </button>
                </div>
            </div>

            <style>
                @media print {
                    /* Hide everything we don't need */
                    nav, header, footer, .filter-section, button, a[href*="export"] {
                        display: none !important;
                    }
                    /* Ensure table and summary cards are visible and look good */
                    body { background: white; }
                    .bg-white, .shadow-xl, .shadow-lg, .rounded-lg { 
                        box-shadow: none !important; 
                        border-radius: 0 !important; 
                    }
                    /* Ensure we print the whole table */
                    .overflow-x-auto { overflow: visible !important; }
                    
                    /* Custom Print Header */
                    .py-12::before {
                        content: "Attendance Report - " attr(data-date);
                        display: block;
                        font-size: 20pt;
                        font-weight: bold;
                        margin-bottom: 20px;
                        text-align: center;
                    }
                }
            </style>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Attendance</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->grand_total ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Men</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_men ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-pink-500 to-rose-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Women</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_women ?? 0) }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Children</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totals->total_children ?? 0) }}</p>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                @if($attendances->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Men</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Women</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Children</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attendances as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $attendance->church->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ ucwords(str_replace('_', ' ', $attendance->service_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $attendance->men_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-pink-600">{{ $attendance->women_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $attendance->children_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ $attendance->total_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $attendances->links() }}</div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No attendance records yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
