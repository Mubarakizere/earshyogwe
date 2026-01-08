<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Evangelism Report Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('evangelism-reports.edit', $evangelismReport) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Edit Report
                </a>
                <a href="{{ route('evangelism-reports.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Report Header Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 border-b pb-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Church</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $evangelismReport->church->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Report Date</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $evangelismReport->report_date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Statistics Grid -->
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <p class="text-xs font-semibold text-blue-600 uppercase">Bible Studies</p>
                            <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($evangelismReport->bible_study_count) }}</p>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                            <p class="text-xs font-semibold text-indigo-600 uppercase">Mentorships</p>
                            <p class="text-2xl font-bold text-indigo-900 mt-1">{{ number_format($evangelismReport->mentorship_count) }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                            <p class="text-xs font-semibold text-purple-600 uppercase">Leadership Training</p>
                            <p class="text-2xl font-bold text-purple-900 mt-1">{{ number_format($evangelismReport->leadership_count) }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <p class="text-xs font-semibold text-green-600 uppercase">New Converts</p>
                            <p class="text-2xl font-bold text-green-900 mt-1">{{ number_format($evangelismReport->converts) }}</p>
                        </div>
                        <div class="bg-teal-50 p-4 rounded-lg border border-teal-100">
                            <p class="text-xs font-semibold text-teal-600 uppercase">Baptized</p>
                            <p class="text-2xl font-bold text-teal-900 mt-1">{{ number_format($evangelismReport->baptized) }}</p>
                        </div>
                        <div class="bg-cyan-50 p-4 rounded-lg border border-cyan-100">
                            <p class="text-xs font-semibold text-cyan-600 uppercase">Confirmed</p>
                            <p class="text-2xl font-bold text-cyan-900 mt-1">{{ number_format($evangelismReport->confirmed) }}</p>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100">
                            <p class="text-xs font-semibold text-emerald-600 uppercase">New Members</p>
                            <p class="text-2xl font-bold text-emerald-900 mt-1">{{ number_format($evangelismReport->new_members) }}</p>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($evangelismReport->notes)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Notes & Observations</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 whitespace-pre-wrap">
                                {{ $evangelismReport->notes }}
                            </div>
                        </div>
                    @endif

                    <!-- Metadata Footer -->
                    <div class="border-t pt-4 mt-8 flex justify-between text-xs text-gray-500">
                        <span>Submitted by: {{ $evangelismReport->submitter->name ?? 'Unknown' }}</span>
                        <span>Created: {{ $evangelismReport->created_at->format('M d, Y H:i') }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
