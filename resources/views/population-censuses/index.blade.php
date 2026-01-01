<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Population Census</h2>
            
            @if(auth()->user()->hasRole('pastor') || auth()->user()->hasRole('boss'))
                <a href="{{ route('population-censuses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
                    + Record Census Data
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white p-4 rounded-lg shadow mb-6">
                <form method="GET" action="{{ route('population-censuses.index') }}" class="flex flex-wrap gap-4 items-end">
                    
                    @if(auth()->user()->hasRole('boss') || auth()->user()->hasRole('archid'))
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Church</label>
                            <select name="church_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">All Churches</option>
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>
                                        {{ $church->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <select name="year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Years</option>
                            @for($y = date('Y'); $y >= 2023; $y--)
                                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700">Filter</button>
                        <a href="{{ route('population-censuses.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($censuses as $census)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $census->year }} - {{ ucfirst($census->period) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $census->church->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                    {{ number_format($census->total) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $census->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($census->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('population-censuses.show', $census) }}" class="text-gray-600 hover:text-gray-900">View</a>
                                    
                                    @if($census->status !== 'approved')
                                        <a href="{{ route('population-censuses.edit', $census) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('population-censuses.destroy', $census) }}" method="POST" class="inline" onsubmit="return confirm('Delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $censuses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
