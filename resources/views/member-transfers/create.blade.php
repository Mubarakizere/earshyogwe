<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('New Member Transfer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                
                <form method="POST" action="{{ route('member-transfers.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Member Selection -->
                        <div>
                            <x-input-label for="member_id" :value="__('Select Member to Transfer')" />
                            <select id="member_id" name="member_id" required class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="">-- Select a Member --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ (isset($selectedMemberId) && $selectedMemberId == $member->id) ? 'selected' : '' }}>
                                        {{ $member->name }} - {{ $member->church->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('member_id')" class="mt-2" />
                        </div>

                        <!-- Destination Parish -->
                        <div>
                            <x-input-label for="to_church_id" :value="__('Transfer To Parish')" />
                            <select id="to_church_id" name="to_church_id" required class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="">-- Select Destination Parish --</option>
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}">{{ $church->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('to_church_id')" class="mt-2" />
                        </div>

                        <!-- Transfer Date -->
                        <div>
                            <x-input-label for="transfer_date" :value="__('Transfer Date')" />
                            <x-text-input id="transfer_date" class="block mt-1 w-full" type="date" name="transfer_date" :value="old('transfer_date', now()->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('transfer_date')" class="mt-2" />
                        </div>

                        <!-- Reason -->
                        <div>
                            <x-input-label for="reason" :value="__('Reason for Transfer (Optional)')" />
                            <textarea id="reason" name="reason" rows="4" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm" placeholder="e.g., Member relocated to a new area, closer to destination parish...">{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-700">
                                    <p class="font-medium">Transfer Process</p>
                                    <ul class="mt-1 list-disc list-inside text-xs">
                                        <li>Once submitted, the destination parish will receive a notification</li>
                                        <li>They can approve or reject the transfer request</li>
                                        <li>Upon approval, the member's parish will be updated automatically</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="{{ route('member-transfers.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Submit Transfer Request') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
