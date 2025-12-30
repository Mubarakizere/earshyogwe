<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Create Activity</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                                <select name="department_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Activity Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Responsible Person</label>
                                <input type="text" name="responsible_person" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target <span class="text-red-500">*</span></label>
                                <input type="number" name="target" min="0" value="0" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" name="start_date" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" name="end_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="planned">Planned</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Documents (Optional)</label>
                            <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            <p class="mt-1 text-sm text-gray-500">Upload proof/evidence documents (Images, PDFs, Word docs, max 5MB each)</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('activities.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md">Create Activity</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
