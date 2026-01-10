<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Attendance Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $attendance->attendance_date->format('M d, Y') }}</h3>
                            <p class="text-lg text-gray-600">{{ $attendance->church->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('attendances.edit', $attendance) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                                Edit
                            </a>
                            <a href="{{ route('attendances.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow transition">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Service Info -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Service Information</h4>
                            <dl class="space-y-3">
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Service Type</dt>
                                    <dd class="text-sm font-bold text-gray-900 col-span-2">
                                        {{ $attendance->serviceType ? $attendance->serviceType->name : 'N/A' }}
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Service Name</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $attendance->service_name ?? 'N/A' }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Day</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $attendance->attendance_date->format('l') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Total Stats -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 flex flex-col justify-center items-center text-center">
                            <h4 class="text-lg font-medium text-blue-800 mb-2">Total Attendance</h4>
                            <div class="text-5xl font-extrabold text-blue-900">{{ $attendance->total_count }}</div>
                            <p class="text-sm text-blue-600 mt-2">Recorded by {{ $attendance->recorder->name ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    <!-- Breakdown -->
                    <h4 class="text-xl font-semibold mb-4 text-gray-800">Demographic Breakdown</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-4 rounded-lg border border-l-4 border-l-blue-500 shadow-sm">
                            <p class="text-sm text-gray-500">Men</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance->men_count }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-l-4 border-l-pink-500 shadow-sm">
                            <p class="text-sm text-gray-500">Women</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance->women_count }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-l-4 border-l-green-500 shadow-sm">
                            <p class="text-sm text-gray-500">Children</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $attendance->children_count }}</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($attendance->notes)
                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Notes</h4>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $attendance->notes }}</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
