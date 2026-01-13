<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Worker - {{ $worker->full_name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('workers.update', $worker) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Personal Information Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $worker->first_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $worker->last_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                                    <select name="gender" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $worker->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $worker->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                    <input type="date" name="birth_date" value="{{ old('birth_date', $worker->birth_date?->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">National ID</label>
                                    <input type="text" name="national_id" value="{{ old('national_id', $worker->national_id) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Education Qualification</label>
                                    <input type="text" name="education_qualification" value="{{ old('education_qualification', $worker->education_qualification) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Contact Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $worker->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $worker->phone) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                    <input type="text" name="district" value="{{ old('district', $worker->district) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Sector</label>
                                    <input type="text" name="sector" value="{{ old('sector', $worker->sector) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Employment Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Institution -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Institution</label>
                                    <select name="institution_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="">Select Institution</option>
                                        @foreach($institutions as $institution)
                                            <option value="{{ $institution->id }}" {{ old('institution_id', $worker->institution_id) == $institution->id ? 'selected' : '' }}>{{ $institution->name }} ({{ $institution->type_name }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Job Title -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="job_title" value="{{ old('job_title', $worker->job_title) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <!-- Employment Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Employment Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="employment_date" value="{{ old('employment_date', $worker->employment_date?->format('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        <option value="active" {{ old('status', $worker->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="retired" {{ old('status', $worker->status) == 'retired' ? 'selected' : '' }}>Retired</option>
                                        <option value="terminated" {{ old('status', $worker->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Documents Section -->
                        @if($worker->documents->count() > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Existing Documents</h3>
                            <div class="space-y-2">
                                @foreach($worker->documents as $document)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $document->document_name }}</p>
                                            <p class="text-sm text-gray-500">Added {{ $document->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <button type="button" onclick="if(confirm('Delete this document?')) { document.getElementById('delete-doc-{{ $document->id }}').submit(); }" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Delete
                                        </button>
                                    </div>
                                    <form id="delete-doc-{{ $document->id }}" action="{{ route('worker-documents.destroy', $document) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Add New Documents Section -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Add New Documents (Optional)</h3>
                            
                            <div id="documents-container" class="space-y-3">
                                <div class="document-row grid grid-cols-12 gap-3">
                                    <div class="col-span-5">
                                        <input type="text" name="document_names[]" placeholder="Document name" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                    <div class="col-span-6">
                                        <input type="file" name="documents[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                    </div>
                                    <div class="col-span-1"></div>
                                </div>
                            </div>
                            
                            <button type="button" onclick="addDocumentRow()" class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                + Add Another Document
                            </button>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('workers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancel</a>
                            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition">Update Worker</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addDocumentRow() {
            const container = document.getElementById('documents-container');
            const row = document.createElement('div');
            row.className = 'document-row grid grid-cols-12 gap-3';
            row.innerHTML = `
                <div class="col-span-5">
                    <input type="text" name="document_names[]" placeholder="Document name" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="col-span-6">
                    <input type="file" name="documents[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div class="col-span-1">
                    <button type="button" onclick="this.closest('.document-row').remove()" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                </div>
            `;
            container.appendChild(row);
        }
    </script>
</x-app-layout>
