<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Revenues Management') }}
            </h2>
            <a href="{{ route('givings.create') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record New Revenue
            </a>

            <div x-data="{ open: false }" class="relative ml-2">
                <button @click="open = !open" type="button" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <a href="{{ route('givings.export', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export as CSV (Excel)
                        </a>
                        <a href="{{ route('givings.exportPdf', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export as PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Received -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Revenue</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-3xl font-bold">{{ number_format($totalAmount, 0) }}</h3>
                            <span class="ml-1 text-lg opacity-80">RWF</span>
                        </div>
                        <p class="text-sm text-blue-200 mt-2">
                            Filtered Period Total
                        </p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Diocese Transfer -->
                <div class="bg-gradient-to-r from-fuchsia-600 to-pink-700 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-fuchsia-100 text-sm font-medium uppercase tracking-wider">Diocese Share</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-3xl font-bold">{{ number_format($sentToDiocese, 0) }}</h3>
                            <span class="ml-1 text-lg opacity-80">RWF</span>
                        </div>
                        <p class="text-sm text-fuchsia-200 mt-2">
                            Marked as Sent
                        </p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pro Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 mb-6">
                <form action="{{ route('givings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <!-- Date Range (Col 1-4) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Parish Filter (Role Based) (Col 5-9) -->
                    <div class="md:col-span-5">
                        @if($churches->count() > 1)
                            <label class="block text-xs font-medium text-gray-500 mb-1">Parish</label>
                            <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Parishes</option>
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                @endforeach
                            </select>
                        @elseif($churches->count() == 1)
                            <label class="block text-xs font-medium text-gray-500 mb-1">Parish</label>
                            <input type="text" disabled value="{{ $churches->first()->name }}" class="w-full text-sm bg-gray-50 rounded-md border-gray-200 text-gray-500 cursor-not-allowed">
                        @endif
                    </div>

                    <!-- Actions (Col 10-12) -->
                    <div class="md:col-span-3 flex space-x-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-3 rounded-md shadow-sm flex items-center justify-center transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ route('givings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium py-2 px-3 rounded-md shadow-sm border border-gray-200 flex items-center justify-center transition" title="Reset Filters">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Content Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <div class="p-0">
                    @if($givings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parish</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Diocese Share</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($givings as $giving)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                {{ \Carbon\Carbon::parse($giving->date)->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs ring-2 ring-white">
                                                        {{ $giving->church ? substr($giving->church->name, 0, 2) : '??' }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $giving->church ? $giving->church->name : 'Parish Not Found' }}</div>
                                                        <div class="text-xs text-gray-500">{{ $giving->last_entered_by ? 'Entry by: '. $giving->enteredBy->name : '' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($giving->total_amount, 0) }}</span>
                                                <span class="text-xs text-gray-500 ml-1">RWF</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="text-sm font-medium text-gray-600">{{ number_format($giving->total_diocese_amount, 0) }}</span>
                                                <span class="text-xs text-gray-500 ml-1">RWF</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($giving->is_sent)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                        Submitted
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('givings.details', ['date' => $giving->date, 'church_id' => $giving->church_id]) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 font-semibold flex items-center justify-end">
                                                    View Details
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $givings->links() }}
                        </div>
                    @else
                        <!-- Existing Empty State -->
                        <div class="text-center py-12">
                            <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No revenues found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or record a new offering.</p>
                            <div class="mt-6">
                                <a href="{{ route('givings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Record Revenue
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
