<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Create Activity</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="space-y-8">
                        {{-- Section 1: Basic Information --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                @if($churches->count() > 1)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                        <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                            <option value="">Select Church</option>
                                            @foreach($churches as $church)
                                                <option value="{{ $church->id }}" {{ old('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('church_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                                    <select name="department_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Activity Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <select name="activity_category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Select Category</option>
                                        <option value="Evangelism" {{ old('activity_category') == 'Evangelism' ? 'selected' : '' }}>Evangelism</option>
                                        <option value="Infrastructure" {{ old('activity_category') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure/Development</option>
                                        <option value="Finance" {{ old('activity_category') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Training" {{ old('activity_category') == 'Training' ? 'selected' : '' }}>Training/Education</option>
                                        <option value="Social" {{ old('activity_category') == 'Social' ? 'selected' : '' }}>Social Services</option>
                                        <option value="Worship" {{ old('activity_category') == 'Worship' ? 'selected' : '' }}>Worship/Liturgy</option>
                                        <option value="Other" {{ old('activity_category') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('activity_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level <span class="text-red-500">*</span></label>
                                    <select name="priority_level" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="low" {{ old('priority_level') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" selected {{ old('priority_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority_level') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('priority_level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                    @error('priority_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Brief overview of the activity</p>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Objectives</label>
                                <textarea name="objectives" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('objectives') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Clear, measurable objectives (what do you want to achieve?)</p>
                                @error('objectives') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expected Outcomes</label>
                                <textarea name="expected_outcomes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('expected_outcomes') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">What results/changes do you expect?</p>
                                @error('expected_outcomes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section 2: Location --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Location</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Location Name</label>
                                    <input type="text" name="location_name" value="{{ old('location_name') }}" placeholder="e.g., Church Hall, Community Center" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('location_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                    <input type="text" name="location_region" value="{{ old('location_region') }}" placeholder="Province/District/Sector" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">Administrative location</p>
                                    @error('location_region') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="location_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('location_address') }}</textarea>
                                @error('location_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Location on Map (Optional)</label>
                                <p class="text-xs text-gray-500 mb-2">Click on the map or drag the marker to select the exact location. You can also use your current location.</p>
                                <x-map-picker 
                                    latitude="{{ old('location_latitude', '-1.9441') }}" 
                                    longitude="{{ old('location_longitude', '30.0619') }}" 
                                    name="location" 
                                />
                                @error('location_latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @error('location_longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section 3: Targets & Beneficiaries --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Targets & Beneficiaries</h3>
                            
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Value <span class="text-red-500">*</span></label>
                                    <input type="number" name="target" value="{{ old('target', 0) }}" required min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Unit</label>
                                    <input type="text" name="target_unit" value="{{ old('target_unit') }}" placeholder="e.g., People, Buildings, RWF" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">What is being measured?</p>
                                    @error('target_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Progress Tracking</label>
                                    <select name="tracking_frequency" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="daily" {{ old('tracking_frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" selected {{ old('tracking_frequency ') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="biweekly" {{ old('tracking_frequency') == 'biweekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="monthly" {{ old('tracking_frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">How often to report progress</p>
                                    @error('tracking_frequency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Beneficiaries</label>
                                <textarea name="target_beneficiaries" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('target_beneficiaries') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Who will benefit from this activity?</p>
                                @error('target_beneficiaries') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section 4: Team & Responsibility --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Team & Responsibility</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Responsible Person</label>
                                <input type="text" name="responsible_person" value="{{ old('responsible_person') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <p class="text-xs text-gray-500 mt-1">Name of the person leading this activity</p>
                                @error('responsible_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section 5: Timeline --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Timeline</h3>
                            
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="planned" selected {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 6: Budget & Funding --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Budget & Funding</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Estimate (RWF)</label>
                                    <input type="number" name="budget_estimate" value="{{ old('budget_estimate') }}" min="0" step="100" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">Estimated cost for this activity</p>
                                    @error('budget_estimate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Funding Source</label>
                                    <select name="funding_source" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Select Source</option>
                                        <option value="Church" {{ old('funding_source') == 'Church' ? 'selected' : '' }}>Church</option>
                                        <option value="Diocese" {{ old('funding_source') == 'Diocese' ? 'selected' : '' }}>Diocese</option>
                                        <option value="Donation" {{ old('funding_source') == 'Donation' ? 'selected' : '' }}>Donation</option>
                                        <option value="Grant" {{ old('funding_source') == 'Grant' ? 'selected' : '' }}>Grant</option>
                                        <option value="Government" {{ old('funding_source') == 'Government' ? 'selected' : '' }}>Government</option>
                                        <option value="Mixed" {{ old('funding_source') == 'Mixed' ? 'selected' : '' }}>Mixed Sources</option>
                                    </select>
                                    @error('funding_source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section 7: Risk Management --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">Risk Management</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Risk Assessment</label>
                                <textarea name="risk_assessment" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('risk_assessment') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">What potential challenges or risks do you foresee?</p>
                                @error('risk_assessment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Mitigation Plan</label>
                                <textarea name="mitigation_plan" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('mitigation_plan') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">How will you handle these risks?</p>
                                @error('mitigation_plan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section 8: Documents --}}
                        <div class="border-t border-gray-200 pt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Documents (Optional)</label>
                            <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                            <p class="mt-1 text-sm text-gray-500">Upload proof/evidence documents (Images, PDFs, Word docs, max 5MB each)</p>
                        </div>

                        {{-- Section 9: Custom Fields (Dynamic based on department) --}}
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-md font-semibold text-gray-900 mb-4">Department-Specific Fields</h3>
                            
                            @foreach($departments as $department)
                                @php
                                    $deptFieldsCount = \App\Models\CustomFieldDefinition::where('department_id', $department->id)
                                        ->active()
                                        ->count();
                                @endphp
                                
                                @if($deptFieldsCount > 0)
                                    <div x-show="document.querySelector('[name=department_id]')?.value == '{{ $department->id }}'" style="display: none;">
                                        <p class="text-sm text-gray-600 mb-4">Custom fields for {{ $department->name }}:</p>
                                        <x-custom-fields-form :departmentId="$department->id" />
                                    </div>
                                @endif
                            @endforeach
                            
                            <p class="text-xs text-gray-500 italic mt-2" x-show="!document.querySelector('[name=department_id]')?.value">
                                Select a department to see if there are any custom fields
                            </p>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('activities.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">Create Activity</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
