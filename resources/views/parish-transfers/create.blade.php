<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Record New Transfer') }}
            </h2>
            <a href="{{ route('parish-transfers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-200">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <div class="p-6 sm:p-8">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Transfer Details</h3>
                        <p class="text-sm text-gray-500">Record a new money transfer to the diocese.</p>
                    </div>

                    <form action="{{ route('parish-transfers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Church Selection -->
                        <div class="mb-6">
                            <label for="church_id" class="block text-sm font-medium text-gray-700 mb-1">Parish <span class="text-red-500">*</span></label>
                            @if($churches->count() == 1)
                                <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg py-3 px-4">
                                    <span class="font-medium text-gray-900">{{ $churches->first()->name }}</span>
                                </div>
                            @else
                                <select name="church_id" id="church_id" required class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('church_id') border-red-500 @enderror">
                                    <option value="">Select Parish</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ old('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('church_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (RWF) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required min="1" step="1" 
                                    class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pl-4 pr-16 @error('amount') border-red-500 @enderror"
                                    placeholder="0">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm font-medium">RWF</span>
                                </div>
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transfer Date -->
                        <div class="mb-6">
                            <label for="transfer_date" class="block text-sm font-medium text-gray-700 mb-1">Transfer Date <span class="text-red-500">*</span></label>
                            <input type="date" name="transfer_date" id="transfer_date" value="{{ old('transfer_date', date('Y-m-d')) }}" required 
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('transfer_date') border-red-500 @enderror">
                            @error('transfer_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference -->
                        <div class="mb-6">
                            <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Reference / Transaction ID</label>
                            <input type="text" name="reference" id="reference" value="{{ old('reference') }}" 
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('reference') border-red-500 @enderror"
                                placeholder="e.g., Bank transfer ID, MoMo reference">
                            @error('reference')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                placeholder="Any additional details about this transfer...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Supporting Document -->
                        <div class="mb-6">
                            <label for="supporting_document" class="block text-sm font-medium text-gray-700 mb-1">Supporting Document (Optional)</label>
                            <input type="file" name="supporting_document" id="supporting_document" accept=".pdf,.jpg,.jpeg,.png"
                                class="w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 @error('supporting_document') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Upload bank slip, receipt, or other proof. Accepted: PDF, JPG, PNG (max 10MB)</p>
                            @error('supporting_document')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Box -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Pending Verification</p>
                                    <p class="text-xs text-blue-600 mt-1">This transfer will be marked as pending until verified by the finance team.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('parish-transfers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-lg shadow-md transition">
                                Record Transfer
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
