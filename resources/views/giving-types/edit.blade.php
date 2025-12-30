<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Giving Type') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Edit Main Type -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('giving-types.update', $givingType) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Edit Giving Type Details</h3>

                    <div class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Giving Type Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $givingType->name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $givingType->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Has Sub-Types -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="has_sub_types" id="has_sub_types" value="1" {{ old('has_sub_types', $givingType->has_sub_types) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    </div>
                                    <div class="ml-3">
                                        <label for="has_sub_types" class="font-medium text-gray-900">Enable Sub-Types</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Is Active -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $givingType->is_active) ? 'checked' : '' }}
                                            class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                    </div>
                                    <div class="ml-3">
                                        <label for="is_active" class="font-medium text-gray-900">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('giving-types.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">
                                Update Giving Type
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Manage Sub-Types -->
            @if($givingType->has_sub_types)
                <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                    <div class="p-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Manage Sub-Types</h3>

                        <!-- Add New Sub-Type Form -->
                        <form action="{{ route('giving-types.sub-types.store', $givingType) }}" method="POST" class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                            @csrf
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Add New Sub-Type</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="sub_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                    <input type="text" name="name" id="sub_name" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="sub_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <input type="text" name="description" id="sub_description"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                                    Add Sub-Type
                                </button>
                            </div>
                        </form>

                        <!-- Existing Sub-Types -->
                        @if($givingType->subTypes->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($givingType->subTypes as $subType)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900">{{ $subType->name }}</h5>
                                                @if($subType->description)
                                                    <p class="mt-1 text-sm text-gray-600">{{ $subType->description }}</p>
                                                @endif
                                            </div>
                                            <form action="{{ route('giving-sub-types.destroy', $subType) }}" method="POST" onsubmit="return confirm('Delete this sub-type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No sub-types added yet. Use the form above to add sub-types.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
