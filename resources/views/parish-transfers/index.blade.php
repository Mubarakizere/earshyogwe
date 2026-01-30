<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Parish Transfers') }}
            </h2>
            @can('create parish transfers')
            <a href="{{ route('parish-transfers.create') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                + New Transfer
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Transfers</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($totalAmount, 0) }} <span class="text-sm text-gray-500 font-normal">RWF</span></h3>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Pending</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ number_format($pendingAmount, 0) }} <span class="text-sm text-gray-500 font-normal">RWF</span></h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $pendingCount }} transfer(s)</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Verified</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ number_format($verifiedAmount, 0) }} <span class="text-sm text-gray-500 font-normal">RWF</span></h3>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">View</p>
                    <h3 class="text-lg font-bold text-gray-700">{{ $transfers->total() }} Records</h3>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-6">
                <form action="{{ route('parish-transfers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    @if($churches->count() > 1)
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Parish</label>
                        <select name="church_id" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Parishes</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md transition">Filter</button>
                        <a href="{{ route('parish-transfers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-4 rounded-md transition">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Transfers Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parish</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entered By</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transfers as $transfer)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $transfer->church->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transfer->transfer_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-lg font-bold text-gray-900">{{ number_format($transfer->amount, 0) }}</span>
                                    <span class="text-xs text-gray-500 ml-1">RWF</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transfer->reference ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transfer->status === 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"/></svg>
                                            Pending
                                        </span>
                                    @elseif($transfer->status === 'verified')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Verified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $transfer->enteredBy->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($transfer->status === 'pending')
                                        @can('verify parish transfers')
                                            <form action="{{ route('parish-transfers.verify', $transfer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Confirm verification?')" class="text-green-600 hover:text-green-900 mr-2">Verify</button>
                                            </form>
                                            <form action="{{ route('parish-transfers.reject', $transfer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Confirm rejection?')" class="text-red-500 hover:text-red-700">Reject</button>
                                            </form>
                                        @endcan
                                    @elseif($transfer->status === 'verified')
                                        <span class="text-xs text-gray-400">
                                            by {{ $transfer->verifiedBy->name ?? 'Unknown' }}
                                            <br>{{ $transfer->verified_at ? $transfer->verified_at->format('M d, Y') : '' }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Closed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="mt-2 text-sm">No transfers found.</p>
                                    @can('create parish transfers')
                                        <a href="{{ route('parish-transfers.create') }}" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-md">Create First Transfer</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($transfers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $transfers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
