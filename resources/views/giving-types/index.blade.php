<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Giving Types Management') }}
            </h2>
            <a href="{{ route('giving-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                <svg class="inline-block w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Giving Type
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($givingTypes as $type)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <!-- Main Giving Type -->
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="text-xl font-bold text-gray-900">{{ $type->name }}</h3>
                                                @if($type->is_active)
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                @else
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                @endif
                                                @if($type->has_sub_types)
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                        {{ $type->subTypes->count() }} Sub-types
                                                    </span>
                                                @endif
                                            </div>
                                            @if($type->description)
                                                <p class="mt-2 text-gray-600">{{ $type->description }}</p>
                                            @endif
                                            <p class="mt-1 text-sm text-gray-500">Created by {{ $type->creator->name ?? 'System' }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('giving-types.edit', $type) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                                Edit
                                            </a>
                                            <form action="{{ route('giving-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this giving type?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sub-Types Section -->
                                @if($type->has_sub_types && $type->subTypes->count() > 0)
                                    <div class="bg-white p-6 border-t border-gray-200">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">Sub-Types</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($type->subTypes as $subType)
                                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <h5 class="font-semibold text-gray-900">{{ $subType->name }}</h5>
                                                            @if($subType->description)
                                                                <p class="mt-1 text-sm text-gray-600">{{ $subType->description }}</p>
                                                            @endif
                                                            @if($subType->is_active)
                                                                <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                                            @else
                                                                <span class="mt-2 inline-block px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                                            @endif
                                                        </div>
                                                        <form action="{{ route('giving-sub-types.destroy', $subType) }}" method="POST" class="ml-2" onsubmit="return confirm('Delete this sub-type?');">
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
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No giving types</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new giving type.</p>
                                <div class="mt-6">
                                    <a href="{{ route('giving-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Giving Type
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
