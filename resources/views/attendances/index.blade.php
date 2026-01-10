<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Attendance Management</h2>
            <a href="{{ route('attendances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Record Attendance
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteRoute: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


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
                        <select name="service_type_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Services</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" {{ request('service_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
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
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attendances as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('attendances.show', $attendance) }}" class="text-gray-900 hover:text-blue-600">
                                                {{ $attendance->attendance_date->format('M d, Y') }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $attendance->church->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ $attendance->serviceType ? $attendance->serviceType->name : 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $attendance->men_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-pink-600">{{ $attendance->women_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-yellow-600">{{ $attendance->children_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ $attendance->total_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('attendances.show', $attendance) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <button 
                                                type="button" 
                                                x-on:click="deleteRoute = '{{ route('attendances.destroy', $attendance) }}'; $dispatch('open-modal', 'confirm-attendance-deletion')"
                                                class="text-red-600 hover:text-red-900">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $attendances->links() }}</div>
                @else
                    <div class="text-center py-12">
                        <x-empty-state 
                            title="No attendance records found" 
                            message="Get started by recording attendance for a service."
                            action="Record Attendance" 
                            url="{{ route('attendances.create') }}"
                            icon="chart-bar"
                        />
                    </div>
                @endif
            </div>

            <!-- Delete Modal -->
            <x-modal name="confirm-attendance-deletion" focusable>
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Delete Attendance Record
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Are you sure you want to delete this attendance record? This action cannot be undone.
                    </p>

                    <div class="mt-6 flex justify-end">
                        <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </button>

                        <form :action="deleteRoute" method="POST" class="ml-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Record
                            </button>
                        </form>
                    </div>
                </div>
            </x-modal>
        </div>
    </div>
</x-app-layout>
