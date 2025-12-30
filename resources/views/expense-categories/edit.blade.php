<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Expense Category</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('expense-categories.update', $expenseCategory) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $expenseCategory->name) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $expenseCategory->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <input type="checkbox" name="requires_approval" id="requires_approval" value="1" {{ old('requires_approval', $expenseCategory->requires_approval) ? 'checked' : '' }}
                                        class="w-4 h-4 text-yellow-600 border-gray-300 rounded mt-1">
                                    <label for="requires_approval" class="ml-3 font-medium text-gray-900">Requires Approval</label>
                                </div>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $expenseCategory->is_active) ? 'checked' : '' }}
                                        class="w-4 h-4 text-green-600 border-gray-300 rounded mt-1">
                                    <label for="is_active" class="ml-3 font-medium text-gray-900">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('expense-categories.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Update Category</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
