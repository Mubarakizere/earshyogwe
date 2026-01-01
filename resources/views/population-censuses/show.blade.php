<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Census Details</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-8">
                
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $populationCensus->church->name }}</h3>
                            <p class="text-gray-600">Census Year: {{ $populationCensus->year }} ({{ ucfirst($populationCensus->period) }})</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $populationCensus->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($populationCensus->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-y-6 gap-x-12">
                    <div class="col-span-2 bg-blue-50 p-6 rounded-xl text-center">
                        <span class="block text-sm text-blue-600 font-semibold uppercase tracking-wider">Total Christian Population</span>
                        <span class="block text-5xl font-extrabold text-blue-900 mt-2">{{ number_format($populationCensus->total) }}</span>
                    </div>

                    <div class="text-center p-4 border rounded-lg">
                        <span class="block text-lg font-semibold text-gray-700">Men</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ number_format($populationCensus->men_count) }}</span>
                    </div>

                    <div class="text-center p-4 border rounded-lg">
                        <span class="block text-lg font-semibold text-gray-700">Women</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ number_format($populationCensus->women_count) }}</span>
                    </div>

                    <div class="text-center p-4 border rounded-lg">
                        <span class="block text-lg font-semibold text-gray-700">Youth</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ number_format($populationCensus->youth_count) }}</span>
                    </div>

                    <div class="text-center p-4 border rounded-lg">
                        <span class="block text-lg font-semibold text-gray-700">Children</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ number_format($populationCensus->children_count) }}</span>
                    </div>
                    
                    <div class="text-center p-4 border rounded-lg col-span-2 w-1/2 mx-auto">
                        <span class="block text-lg font-semibold text-gray-700">Infants</span>
                        <span class="block text-2xl font-bold text-gray-900">{{ number_format($populationCensus->infants_count) }}</span>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('population-censuses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Back to List</a>
                    @if($populationCensus->status !== 'approved')
                        <a href="{{ route('population-censuses.edit', $populationCensus) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Edit Data</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
