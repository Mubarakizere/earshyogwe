<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Attendance</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('attendances.update', $attendance) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            @if($churches->count() > 1)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                    <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}" {{ $attendance->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="church_id" value="{{ $attendance->church_id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="attendance_date" value="{{ $attendance->attendance_date->format('Y-m-d') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Type <span class="text-red-500">*</span></label>
                                <select name="service_type_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="">Select Type</option>
                                    @foreach($serviceTypes as $type)
                                        <option value="{{ $type->id }}" {{ $attendance->service_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service Name (Optional)</label>
                                <input type="text" name="service_name" value="{{ $attendance->service_name }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <!-- Demographics Section -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Count</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Men <span class="text-red-500">*</span></label>
                                    <input type="number" name="men_count" min="0" value="{{ $attendance->men_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Women <span class="text-red-500">*</span></label>
                                    <input type="number" name="women_count" min="0" value="{{ $attendance->women_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Children <span class="text-red-500">*</span></label>
                                    <input type="number" name="children_count" min="0" value="{{ $attendance->children_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg">{{ $attendance->notes }}</textarea>
                        </div>

                        <!-- Existing Documents Section -->
                        @if($attendance->documents && $attendance->documents->count() > 0)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attached Documents</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($attendance->documents as $document)
                                <div class="relative border rounded-lg p-3 bg-gray-50">
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
                                    <p class="text-xs text-gray-600 truncate">{{ $document->original_name }}</p>
                                    <form action="{{ route('attendance-documents.destroy', $document) }}" method="POST" class="absolute top-1 right-1" onsubmit="return confirm('Delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
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
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Add Supporting Documents</h3>
                            <p class="text-sm text-gray-500 mb-4">Optional: Attach PDF or image files</p>
                            <div>
                                <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-400 mt-1">Accepted formats: PDF, JPG, PNG. Max 10MB per file.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                             <!-- Delete Button (Left Aligned) -->
                            <button type="button" onclick="if(confirm('Are you sure?')) document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-medium">
                                Delete Record
                            </button>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('attendances.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Update Attendance</button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
