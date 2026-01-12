<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Directorate') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('departments.update', $department) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        @if($churches->count() > 1)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parish <span class="text-red-500">*</span></label>
                                <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Select Parish</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ $department->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                                @error('church_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $department->church_id }}">
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Directorate Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $department->name) }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                             @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('description', $department->description) }}</textarea>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ $department->is_active ? 'checked' : '' }} class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label class="ml-2 block text-sm text-gray-900"> Active </label>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('departments.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md">Update Directorate</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
