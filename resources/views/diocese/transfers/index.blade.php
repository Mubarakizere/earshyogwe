<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incoming Transfers Verification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    @if($pendingTransfers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Ref</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Church</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entered By</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingTransfers as $giving)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $giving->date->format('Y-m-d') }}
                                                @if($giving->transfer_reference)
                                                    <div class="text-xs text-gray-500">{{ $giving->transfer_reference }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $giving->church->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                                {{ number_format($giving->amount) }} RWF
                                                @if($giving->diocese_amount > 0 && $giving->diocese_amount != $giving->amount)
                                                    <div class="text-xs text-gray-500">Exp. Share: {{ number_format($giving->diocese_amount) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $giving->enteredBy->name ?? 'N/A' }}
                                                <div class="text-xs">Sent: {{ $giving->diocese_sent_date ? $giving->diocese_sent_date->format('M d') : '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <form action="{{ route('diocese.transfers.verify', $giving) }}" method="POST" class="inline-block" onsubmit="return confirm('Confirm receipt of funds?');">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 font-bold bg-green-50 px-3 py-1 rounded border border-green-200">Verify Receipt</button>
                                                </form>
                                                <form action="{{ route('diocese.transfers.reject', $giving) }}" method="POST" class="inline-block" onsubmit="return confirm('Reject this transfer record?');">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded border border-red-200">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $pendingTransfers->links() }}
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Pending Transfers</h3>
                            <p class="mt-1 text-sm text-gray-500">All sent transfers have been verified.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
