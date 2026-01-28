<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Attendance') }}
            </h2>
            <a href="{{ route('attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    
                    <form action="{{ route('attendances.update', $attendance) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Parish -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parish</label>
                                <select name="church_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ $attendance->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                                @error('church_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Attendance Date</label>
                                <input type="date" name="attendance_date" value="{{ $attendance->attendance_date->format('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('attendance_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Service Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type</label>
                                <select name="service_type_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ $attendance->service_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('service_type_id')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Service Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Name (Optional)</label>
                                <input type="text" name="service_name" value="{{ $attendance->service_name }}" placeholder="e.g. Morning Service" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Men Count -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Men</label>
                                <input type="number" name="men_count" value="{{ $attendance->men_count }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('men_count')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Women Count -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Women</label>
                                <input type="number" name="women_count" value="{{ $attendance->women_count }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('women_count')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Children Count -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Children</label>
                                <input type="number" name="children_count" value="{{ $attendance->children_count }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                @error('children_count')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Notes (full width) -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional notes...">{{ $attendance->notes }}</textarea>
                            </div>
                        </div>

                        <!-- Existing Documents Section -->
                        @if($attendance->documents && $attendance->documents->count() > 0)
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attached Documents</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($attendance->documents as $document)
                                <div class="relative border rounded-lg p-3 bg-gray-50 group">
                                    @if($document->is_image)
                                        <a href="{{ $document->url }}" target="_blank">
                                            <img src="{{ $document->url }}" alt="{{ $document->original_name }}" class="w-full h-24 object-cover rounded mb-2">
                                        </a>
                                    @else
                                        <a href="{{ $document->url }}" target="_blank" class="flex items-center justify-center h-24 bg-red-50 rounded mb-2">
                                            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                    <p class="text-xs text-gray-600 truncate" title="{{ $document->original_name }}">{{ $document->original_name }}</p>
                                    <!-- Delete button using direct URL -->
                                    <form action="/attendance-documents/{{ $document->id }}" method="POST" class="absolute top-1 right-1" onsubmit="return confirm('Delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Add New Documents Section -->
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Documents</h3>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">Upload PDF or images (optional)</p>
                                <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" class="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('attendances.show', $attendance) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                Update Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
