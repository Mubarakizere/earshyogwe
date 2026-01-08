<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Evangelism Report</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('evangelism-reports.update', $evangelismReport) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        @if($churches->count() > 1)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="">Select Church</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ $evangelismReport->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                                @error('church_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $evangelismReport->church_id }}">
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Report Date <span class="text-red-500">*</span></label>
                            <input type="date" name="report_date" value="{{ $evangelismReport->report_date->format('Y-m-d') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('report_date') border-red-500 @enderror">
                            @error('report_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discipleship & Growth -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Discipleship & Growth</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bible Study</label>
                                    <input type="number" name="bible_study_count" min="0" value="{{ $evangelismReport->bible_study_count }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Mentorship</label>
                                    <input type="number" name="mentorship_count" min="0" value="{{ $evangelismReport->mentorship_count }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Leadership</label>
                                    <input type="number" name="leadership_count" min="0" value="{{ $evangelismReport->leadership_count }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- Evangelism Impacts -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Evangelism Impacts</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Converts</label>
                                    <input type="number" name="converts" min="0" value="{{ $evangelismReport->converts }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Baptized</label>
                                    <input type="number" name="baptized" min="0" value="{{ $evangelismReport->baptized }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmed</label>
                                    <input type="number" name="confirmed" min="0" value="{{ $evangelismReport->confirmed }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Members</label>
                                    <input type="number" name="new_members" min="0" value="{{ $evangelismReport->new_members }}" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ $evangelismReport->notes }}</textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('evangelism-reports.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md">Update Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
