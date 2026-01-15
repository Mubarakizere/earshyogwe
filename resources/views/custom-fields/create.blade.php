<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Create Custom Field</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-8">
                <form action="{{ route('custom-fields.store') }}" method="POST" x-data="{ fieldType: 'text' }">
                    @csrf

                    <div class="space-y-6">
                        <!-- Department -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                            <select name="department_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Field Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Field Name <span class="text-red-500">*</span></label>
                            <input type="text" name="field_name" value="{{ old('field_name') }}" required placeholder="e.g., Number of Bibles Distributed" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">A descriptive name for this field</p>
                            @error('field_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Field Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Field Type <span class="text-red-500">*</span></label>
                            <select name="field_type" x-model="fieldType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <option value="text">Text (Single Line)</option>
                                <option value="textarea">Text Area (Multiple Lines)</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="select">Dropdown Select</option>
                                <option value="checkbox">Checkbox (Yes/No)</option>
                            </select>
                            @error('field_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Field Options (for select) -->
                        <div x-show="fieldType === 'select'" x-data="{ options: [''] }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dropdown Options</label>
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'field_options[' + index + ']'" x-model="options[index]" placeholder="Option value" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <button type="button" @click="options.splice(index, 1)" x-show="options.length > 1" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Remove</button>
                                </div>
                            </template>
                            <button type="button" @click="options.push('')" class="text-purple-600 hover:text-purple-700 text-sm font-medium">+ Add Option</button>
                        </div>

                        <!-- Is Required -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required') ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_required" class="ml-2 block text-sm text-gray-700">Make this field required</label>
                        </div>

                        <!-- Help Text -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Help Text (Optional)</label>
                            <textarea name="help_text" rows="2" placeholder="Instructions or guidance for users filling this field" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('help_text') }}</textarea>
                            @error('help_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Display Order -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                            @error('display_order') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('custom-fields.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">Create Custom Field</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
