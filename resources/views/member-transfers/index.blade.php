<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Member Transfers') }}
            </h2>
            <a href="{{ route('member-transfers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                New Transfer
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
                <form action="{{ route('member-transfers.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    @if(isset($churches) && $churches->isNotEmpty())
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Parish</label>
                        <select name="church_id" class="w-full py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Parishes</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                        <select name="status" class="w-full py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Incoming Transfers -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-8">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        Incoming Transfers (To Your Parish)
                    </h3>
                    <p class="text-sm text-green-100 mt-1">Members requesting to join your parish</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Parish</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transfer Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($incomingTransfers as $transfer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                                {{ substr($transfer->member->name ?? 'N', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $transfer->member->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">{{ $transfer->member->sex ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $transfer->fromChurch->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transfer->transfer_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transfer->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transfer->initiatedBy->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('member-transfers.show', $transfer) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        @if($transfer->status === 'pending')
                                            <form action="{{ route('member-transfers.approve', $transfer) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        <p class="text-sm">No incoming transfer requests.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($incomingTransfers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $incomingTransfers->appends(request()->except('incoming_page'))->links() }}
                </div>
                @endif
            </div>

            <!-- Outgoing Transfers -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        Outgoing Transfers (From Your Parish)
                    </h3>
                    <p class="text-sm text-blue-100 mt-1">Members you've requested to transfer out</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Parish</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transfer Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($outgoingTransfers as $transfer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                                {{ substr($transfer->member->name ?? 'N', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900">{{ $transfer->member->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">{{ $transfer->member->sex ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $transfer->toChurch->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transfer->transfer_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transfer->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($transfer->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('member-transfers.show', $transfer) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        @if($transfer->status === 'pending')
                                            <form action="{{ route('member-transfers.destroy', $transfer) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this transfer request?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <p class="text-sm">No outgoing transfer requests.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($outgoingTransfers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $outgoingTransfers->appends(request()->except('outgoing_page'))->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
