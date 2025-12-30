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
