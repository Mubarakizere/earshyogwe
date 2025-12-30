<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Expense Categories</h2>
            <a href="{{ route('expense-categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Add Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($categories as $category)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                                <div class="flex gap-2">
                                    @if($category->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                    @endif
                                    @if($category->requires_approval)
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Approval Required</span>
                                    @endif
                                </div>
                            </div>
                            @if($category->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $category->description }}</p>
                            @endif
                            <div class="flex gap-2">
                                <a href="{{ route('expense-categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                <form action="{{ route('expense-categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500">No categories yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
