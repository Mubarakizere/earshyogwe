<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Record Attendance</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('attendances.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            @if($churches->count() > 1)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                    <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="">Select Church</option>
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}">{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type <span class="text-red-500">*</span></label>
                                <select name="service_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="sunday_service">Sunday Service</option>
                                    <option value="prayer_meeting">Prayer Meeting</option>
                                    <option value="bible_study">Bible Study</option>
                                    <option value="youth_service">Youth Service</option>
                                    <option value="special_event">Special Event</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Name (Optional)</label>
                                <input type="text" name="service_name" placeholder="e.g., Easter Service" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <!-- Demographics Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Count</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Men <span class="text-red-500">*</span></label>
                                    <input type="number" name="men_count" min="0" value="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Women <span class="text-red-500">*</span></label>
                                    <input type="number" name="women_count" min="0" value="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Children <span class="text-red-500">*</span></label>
                                    <input type="number" name="children_count" min="0" value="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('attendances.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Record Attendance</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
