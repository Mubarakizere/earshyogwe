<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Service Types Management') }}
            </h2>
            @can('manage service types')
            <a href="{{ route('service-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                <svg class="inline-block w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Service Type
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($serviceTypes as $type)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
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
                                            </div>
                                            @if($type->description)
                                                <p class="mt-2 text-gray-600">{{ $type->description }}</p>
                                            @endif
                                        </div>
                                        @can('manage service types')
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('service-types.edit', $type) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                                Edit
                                            </a>
                                            
                                            <form action="{{ route('service-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this service type? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No service types</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new service type.</p>
                                @can('manage service types')
                                <div class="mt-6">
                                    <a href="{{ route('service-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Add Service Type
                                    </a>
                                </div>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
