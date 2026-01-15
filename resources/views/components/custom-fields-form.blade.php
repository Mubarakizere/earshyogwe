{{-- Dynamic Custom Fields Component --}}
@props(['departmentId', 'activityId' => null])

@php
    $customFields = \App\Models\CustomFieldDefinition::query()
        ->where('department_id', $departmentId)
        ->active()
        ->ordered()
        ->get();
    
    $existingValues = [];
    if ($activityId) {
        $existingValues = \App\Models\ActivityCustomValue::where('activity_id', $activityId)
            ->get()
            ->keyBy('custom_field_definition_id')
            ->map(fn($val) => $val->field_value);
    }
@endphp

@if($customFields->count() > 0)
    <div class="space-y-4">
        <h4 class="text-md font-semibold text-gray-900 border-b pb-2">Department-Specific Fields</h4>
        
        @foreach($customFields as $field)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $field->field_name }}
                    @if($field->is_required)
                        <span class="text-red-500">*</span>
                    @endif
                </label>

                @if($field->field_type === 'text')
                    <input type="text" 
                        name="custom_fields[{{ $field->id }}]" 
                        value="{{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') }}"
                        {{ $field->is_required ? 'required' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">

                @elseif($field->field_type === 'textarea')
                    <textarea 
                        name="custom_fields[{{ $field->id }}]" 
                        rows="3"
                        {{ $field->is_required ? 'required' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') }}</textarea>

                @elseif($field->field_type === 'number')
                    <input type="number" 
                        name="custom_fields[{{ $field->id }}]" 
                        value="{{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') }}"
                        {{ $field->is_required ? 'required' : '' }}
                        step="any"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">

                @elseif($field->field_type === 'date')
                    <input type="date" 
                        name="custom_fields[{{ $field->id }}]" 
                        value="{{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') }}"
                        {{ $field->is_required ? 'required' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">

                @elseif($field->field_type === 'select')
                    <select 
                        name="custom_fields[{{ $field->id }}]" 
                        {{ $field->is_required ? 'required' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select an option</option>
                        @foreach($field->field_options as $option)
                            <option value="{{ $option }}" 
                                {{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>

                @elseif($field->field_type === 'checkbox')
                    <div class="flex items-center">
                        <input type="checkbox" 
                            name="custom_fields[{{ $field->id }}]" 
                            value="1"
                            {{ old('custom_fields.' . $field->id, $existingValues[$field->id] ?? '') ? 'checked' : '' }}
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-700">Yes</label>
                    </div>
                @endif

                @if($field->help_text)
                    <p class="text-xs text-gray-500 mt-1">{{ $field->help_text }}</p>
                @endif
                
                @error('custom_fields.' . $field->id) 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>
        @endforeach
    </div>
@endif
