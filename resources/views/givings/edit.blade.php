<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Offering') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('givings.update', $giving) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Parish Selection (if applicable or view only) -->
                        @if($churches->count() > 1)
                            <div>
                                <label for="church_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Parish <span class="text-red-500">*</span>
                                </label>
                                <select name="church_id" id="church_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('church_id') border-red-500 @enderror">
                                    <option value="">Select Parish</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ old('church_id', $giving->church_id) == $church->id ? 'selected' : '' }}>
                                            {{ $church->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('church_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $giving->church_id }}">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-blue-900">Editing for: <strong>{{ $giving->church->name }}</strong></p>
                            </div>
                        @endif

                        <!-- Giving Type -->
                        <div>
                            <label for="giving_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Offering Type <span class="text-red-500">*</span>
                            </label>
                            <select name="giving_type_id" id="giving_type_id" required onchange="updateSubTypes()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('giving_type_id') border-red-500 @enderror">
                                <option value="">Select Offering Type</option>
                                @foreach($givingTypes as $type)
                                    <option value="{{ $type->id }}" 
                                        data-has-subtypes="{{ $type->has_sub_types }}" 
                                        data-subtypes="{{ $type->subTypes->toJson() }}" 
                                        {{ old('giving_type_id', $giving->giving_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('giving_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sub-Type (conditional) -->
                        <div id="sub_type_container" style="display: none;">
                            <label for="giving_sub_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Sub-Type
                            </label>
                            <select name="giving_sub_type_id" id="giving_sub_type_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Sub-Type</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Amount (RWF) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $giving->amount) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('amount') border-red-500 @enderror">
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" id="date" value="{{ old('date', $giving->date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('date') border-red-500 @enderror">
                                @error('date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes', $giving->notes) }}</textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('givings.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-semibold rounded-lg shadow-md">
                                Update Offering
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateSubTypes(currentSubTypeId = null) {
            const select = document.getElementById('giving_type_id');
            const option = select.options[select.selectedIndex];
            
            if (!option) return;

            const hasSubTypes = option.dataset.hasSubtypes === '1';
            const subTypesContainer = document.getElementById('sub_type_container');
            const subTypeSelect = document.getElementById('giving_sub_type_id');

            if (hasSubTypes) {
                const subTypes = JSON.parse(option.dataset.subtypes || '[]');
                subTypeSelect.innerHTML = '<option value="">Select Sub-Type</option>';
                subTypes.forEach(subType => {
                    const opt = document.createElement('option');
                    opt.value = subType.id;
                    opt.textContent = subType.name;
                    if (currentSubTypeId && subType.id == currentSubTypeId) {
                        opt.selected = true;
                    }
                    subTypeSelect.appendChild(opt);
                });
                subTypesContainer.style.display = 'block';
            } else {
                subTypesContainer.style.display = 'none';
                subTypeSelect.innerHTML = '<option value="">Select Sub-Type</option>';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Pass the current subtype ID if it exists
            const currentSubTypeId = "{{ old('giving_sub_type_id', $giving->giving_sub_type_id) }}";
            updateSubTypes(currentSubTypeId);
        });
    </script>
</x-app-layout>
