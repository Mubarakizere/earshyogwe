<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Census Data</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-8">
                <form action="{{ route('population-censuses.update', $populationCensus) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="bg-blue-50 p-4 rounded-lg flex justify-between items-center">
                            <div>
                                <span class="block text-sm text-gray-600">Period</span>
                                <span class="font-bold text-gray-800">{{ $populationCensus->year }} - {{ ucfirst($populationCensus->period) }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-600">Church</span>
                                <span class="font-bold text-gray-800">{{ $populationCensus->church->name }}</span>
                            </div>
                        </div>

                        <!-- Member Counts -->
                        <h3 class="text-lg font-semibold text-gray-800">Update Counts</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Men (Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="men_count" min="0" value="{{ $populationCensus->men_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Women (Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="women_count" min="0" value="{{ $populationCensus->women_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Youth (Young Adults) <span class="text-red-500">*</span></label>
                                <input type="number" name="youth_count" min="0" value="{{ $populationCensus->youth_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Children (Sunday School) <span class="text-red-500">*</span></label>
                                <input type="number" name="children_count" min="0" value="{{ $populationCensus->children_count }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Infants (0-3 Years) <span class="text-gray-400 text-xs">(Optional)</span></label>
                                <input type="number" name="infants_count" min="0" value="{{ $populationCensus->infants_count }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4">
                            <a href="{{ route('population-censuses.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
