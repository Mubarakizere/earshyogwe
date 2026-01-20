<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Record Offering') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('givings.store') }}" method="POST" class="p-8" id="givingForm">
                    @csrf

                    {{-- Validation Errors Display --}}
                    @if ($errors->any() || session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 mb-2">Please correct the following errors:</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @if(session('error'))
                                            <li>{{ session('error') }}</li>
                                        @endif
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Parish Selection -->
                        @if($churches->count() > 1)
                            <div>
                                <label for="church_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Parish <span class="text-red-500">*</span>
                                </label>
                                <select name="church_id" id="church_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('church_id') border-red-500 @enderror">
                                    <option value="">Select Parish</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ old('church_id') == $church->id ? 'selected' : '' }}>
                                            {{ $church->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('church_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center">
                                <span class="text-blue-500 mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </span>
                                <p class="text-sm font-medium text-blue-900">Recording for: <strong>{{ $churches->first()->name }}</strong></p>
                            </div>
                        @endif

                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Enter Amounts</h3>
                        <div class="space-y-8">
                            @foreach($givingTypes as $type)
                                @if($type->has_sub_types && $type->subTypes->count() > 0)
                                    {{-- Parent type with subtypes: Show as section title --}}
                                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border-l-4 border-blue-500">
                                        <h4 class="text-base font-semibold text-gray-800 mb-4 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {{ $type->name }}
                                        </h4>
                                        @if($type->description)
                                            <p class="text-sm text-gray-600 mb-4 ml-7">{{ $type->description }}</p>
                                        @endif
                                        
                                        {{-- Display subtypes as input fields --}}
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 ml-7">
                                            @foreach($type->subTypes as $subType)
                                                <div>
                                                    <label for="subtype_{{ $subType->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                        {{ $subType->name }}
                                                    </label>
                                                    <div class="flex rounded-md shadow-sm">
                                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-medium">
                                                            RWF
                                                        </span>
                                                        <input type="number" name="subtypes[{{ $subType->id }}]" id="subtype_{{ $subType->id }}" 
                                                            min="0" step="1" placeholder=""
                                                            class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 py-2.5">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    {{-- Regular type without subtypes: Show as input field --}}
                                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                                        <label for="amount_{{ $type->id }}" class="block text-sm font-semibold text-gray-800 mb-2">
                                            {{ $type->name }}
                                        </label>
                                        @if($type->description)
                                            <p class="text-xs text-gray-600 mb-3">{{ $type->description }}</p>
                                        @endif
                                        <div class="flex rounded-md shadow-sm max-w-md">
                                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm font-medium">
                                                RWF
                                            </span>
                                            <input type="number" name="amounts[{{ $type->id }}]" id="amount_{{ $type->id }}" 
                                                min="0" step="1" placeholder=""
                                                class="focus:ring-blue-500 focus:border-blue-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 py-2.5">
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="2"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('givings.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform transition hover:-translate-y-0.5 duration-150 ease-in-out">
                            Save Offerings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('givingForm').addEventListener('submit', function(e) {
            // Get all amount input fields
            const amountInputs = document.querySelectorAll('input[name^="amounts["], input[name^="subtypes["]');
            let hasValue = false;

            // Check if at least one amount field has a value > 0
            amountInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                if (value > 0) {
                    hasValue = true;
                }
            });

            // If no amounts entered, prevent submission and show alert
            if (!hasValue) {
                e.preventDefault();
                
                // Create and show error message
                const form = this;
                let errorDiv = document.getElementById('client-validation-error');
                
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.id = 'client-validation-error';
                    errorDiv.className = 'mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg';
                    errorDiv.innerHTML = `
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-red-800 mb-1">Validation Error</h3>
                                <p class="text-sm text-red-700">Please enter at least one offering amount before submitting.</p>
                            </div>
                            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    `;
                    
                    // Insert error message at the top of the form, after CSRF token
                    const firstChild = form.querySelector('.grid');
                    form.insertBefore(errorDiv, firstChild);
                }
                
                // Scroll to error message
                errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                return false;
            }
        });

        // Remove client-side error when user starts entering values
        document.querySelectorAll('input[name^="amounts["], input[name^="subtypes["]').forEach(input => {
            input.addEventListener('input', function() {
                const errorDiv = document.getElementById('client-validation-error');
                if (errorDiv && this.value > 0) {
                    errorDiv.remove();
                }
            });
        });
    </script>
</x-app-layout>
