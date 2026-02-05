<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Transfer Details') }}
            </h2>
            <a href="{{ route('member-transfers.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Transfers
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Status Banner -->
            @php
                $statusBanners = [
                    'pending' => ['bg' => 'bg-yellow-50 border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'text-yellow-400'],
                    'approved' => ['bg' => 'bg-green-50 border-green-200', 'text' => 'text-green-800', 'icon' => 'text-green-400'],
                    'rejected' => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-800', 'icon' => 'text-red-400'],
                ];
                $banner = $statusBanners[$memberTransfer->status] ?? $statusBanners['pending'];
            @endphp
            <div class="mb-6 rounded-lg border p-4 {{ $banner['bg'] }}">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 {{ $banner['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($memberTransfer->status === 'approved')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($memberTransfer->status === 'rejected')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                    <div>
                        <p class="font-bold {{ $banner['text'] }}">{{ ucfirst($memberTransfer->status) }} Transfer</p>
                        @if($memberTransfer->status === 'approved')
                            <p class="text-sm {{ $banner['text'] }}">Approved by {{ $memberTransfer->approvedBy->name ?? 'N/A' }} on {{ $memberTransfer->approved_at->format('M d, Y H:i') }}</p>
                        @elseif($memberTransfer->status === 'rejected')
                            <p class="text-sm {{ $banner['text'] }}">Reason: {{ $memberTransfer->rejection_reason ?? 'No reason provided' }}</p>
                        @else
                            <p class="text-sm {{ $banner['text'] }}">Awaiting approval from destination parish</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <!-- Transfer Details -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Transfer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Member</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $memberTransfer->member->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">{{ $memberTransfer->member->sex ?? '' }} • {{ $memberTransfer->member->age ? $memberTransfer->member->age . ' years' : '' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Transfer Date</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->transfer_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">From Parish</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->fromChurch->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">To Parish</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->toChurch->name ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase">Reason</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->reason ?? 'No reason provided' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Requested By</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->initiatedBy->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Requested On</label>
                            <p class="mt-1 text-gray-900">{{ $memberTransfer->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions (for pending transfers) -->
                @if($memberTransfer->status === 'pending')
                <div class="p-6 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                    
                    <div class="flex flex-wrap gap-4">
                        <!-- Approve Button -->
                        <form action="{{ route('member-transfers.approve', $memberTransfer) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Approve Transfer
                            </button>
                        </form>

                        <!-- Reject Button (with reason modal) -->
                        <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'reject-transfer')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Reject Transfer
                        </button>

                        <!-- Cancel (Delete) for initiator -->
                        <form action="{{ route('member-transfers.destroy', $memberTransfer) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this transfer request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Cancel Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject Modal -->
                <x-modal name="reject-transfer" focusable>
                    <form action="{{ route('member-transfers.reject', $memberTransfer) }}" method="POST" class="p-6">
                        @csrf
                        <h2 class="text-lg font-medium text-gray-900">
                            Reject Transfer Request
                        </h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Please provide a reason for rejecting this transfer request.
                        </p>
                        <div class="mt-4">
                            <textarea name="rejection_reason" rows="4" class="w-full border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" placeholder="Optional reason for rejection..."></textarea>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase hover:bg-red-700 transition">
                                Confirm Rejection
                            </button>
                        </div>
                    </form>
                </x-modal>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
