<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Transfer Details') }}
            </h2>
            <a href="{{ route('parish-transfers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-200">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ modalType: '' }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-2xl border border-gray-100">
                
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-200 text-sm font-medium uppercase tracking-wider">Transfer Amount</p>
                            <h1 class="text-4xl font-bold mt-1">{{ number_format($transfer->amount, 0) }} <span class="text-xl font-normal opacity-80">RWF</span></h1>
                        </div>
                        <div class="text-right">
                            @if($transfer->status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-400 text-yellow-900">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"/></svg>
                                    Pending Verification
                                </span>
                            @elseif($transfer->status === 'verified')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-400 text-green-900">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-400 text-red-900">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="px-6 py-6 space-y-6">
                    
                    <!-- Parish & Date -->
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Parish</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $transfer->church->name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Transfer Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $transfer->transfer_date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Reference -->
                    @if($transfer->reference)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Reference / Transaction ID</p>
                        <p class="text-base font-medium text-gray-900 font-mono">{{ $transfer->reference }}</p>
                    </div>
                    @endif

                    <!-- Notes -->
                    @if($transfer->notes)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Notes</p>
                        <p class="text-base text-gray-700">{{ $transfer->notes }}</p>
                    </div>
                    @endif

                    <!-- Audit Info -->
                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Audit Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Entered By</p>
                                <p class="font-medium text-gray-900">{{ $transfer->enteredBy->name ?? 'Unknown' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Created At</p>
                                <p class="font-medium text-gray-900">{{ $transfer->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            @if($transfer->status !== 'pending')
                            <div>
                                <p class="text-gray-500">{{ $transfer->status === 'verified' ? 'Verified By' : 'Rejected By' }}</p>
                                <p class="font-medium text-gray-900">{{ $transfer->verifiedBy->name ?? 'Unknown' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ $transfer->status === 'verified' ? 'Verified At' : 'Rejected At' }}</p>
                                <p class="font-medium text-gray-900">{{ $transfer->verified_at ? $transfer->verified_at->format('M d, Y H:i') : '-' }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Footer -->
                @if($transfer->status === 'pending')
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    @if(auth()->id() == $transfer->entered_by || auth()->user()->can('view all transfers'))
                        <button type="button"
                            x-on:click="modalType = 'delete'; $dispatch('open-modal', 'confirm-show-action')"
                            class="inline-flex items-center px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Delete Transfer
                        </button>
                    @else
                        <div></div>
                    @endif

                    @can('verify parish transfers')
                        <div class="flex space-x-3">
                            <button type="button"
                                x-on:click="modalType = 'reject'; $dispatch('open-modal', 'confirm-show-action')"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reject
                            </button>
                            <button type="button"
                                x-on:click="modalType = 'verify'; $dispatch('open-modal', 'confirm-show-action')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Verify Transfer
                            </button>
                        </div>
                    @endcan
                </div>
                @endif

            </div>
        </div>

        <!-- Confirmation Modal -->
        <x-modal name="confirm-show-action" focusable>
            <div class="p-6">
                <!-- Modal Header with Icon -->
                <div class="flex items-center justify-center mb-4">
                    <template x-if="modalType === 'verify'">
                        <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </template>
                    <template x-if="modalType === 'reject'">
                        <div class="w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </template>
                    <template x-if="modalType === 'delete'">
                        <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                    </template>
                </div>

                <!-- Modal Title -->
                <h2 class="text-xl font-bold text-gray-900 text-center mb-2">
                    <template x-if="modalType === 'verify'">
                        <span>Verify Transfer</span>
                    </template>
                    <template x-if="modalType === 'reject'">
                        <span>Reject Transfer</span>
                    </template>
                    <template x-if="modalType === 'delete'">
                        <span>Delete Transfer</span>
                    </template>
                </h2>

                <!-- Modal Description -->
                <p class="text-sm text-gray-600 text-center mb-6">
                    <template x-if="modalType === 'verify'">
                        <span>Are you sure you want to verify this transfer of <strong>{{ number_format($transfer->amount, 0) }} RWF</strong> from <strong>{{ $transfer->church->name ?? 'Unknown' }}</strong>?</span>
                    </template>
                    <template x-if="modalType === 'reject'">
                        <span>Are you sure you want to reject this transfer of <strong>{{ number_format($transfer->amount, 0) }} RWF</strong> from <strong>{{ $transfer->church->name ?? 'Unknown' }}</strong>?</span>
                    </template>
                    <template x-if="modalType === 'delete'">
                        <span>Are you sure you want to delete this transfer of <strong>{{ number_format($transfer->amount, 0) }} RWF</strong> from <strong>{{ $transfer->church->name ?? 'Unknown' }}</strong>? This action cannot be undone.</span>
                    </template>
                </p>

                <!-- Modal Actions -->
                <div class="flex justify-center gap-3">
                    <button type="button" x-on:click="$dispatch('close')" 
                        class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                        Cancel
                    </button>

                    <template x-if="modalType === 'verify'">
                        <form action="{{ route('parish-transfers.verify', $transfer) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Verify Transfer
                            </button>
                        </form>
                    </template>
                    <template x-if="modalType === 'reject'">
                        <form action="{{ route('parish-transfers.reject', $transfer) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reject Transfer
                            </button>
                        </form>
                    </template>
                    <template x-if="modalType === 'delete'">
                        <form action="{{ route('parish-transfers.destroy', $transfer) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete Transfer
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>
