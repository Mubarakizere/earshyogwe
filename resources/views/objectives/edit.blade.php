<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Objective: {{ $objective->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('objectives.update', $objective) }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ currentTab: 'basic', hasCustomFields: {{ \App\Models\CustomFieldDefinition::where('department_id', $objective->department_id)->active()->count() > 0 ? 'true' : 'false' }} }">
                    @csrf
                    @method('PUT')

                    {{-- Tab Navigation --}}
                    <div class="border-b border-gray-200 bg-gray-50 px-6 pt-6">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <button type="button" @click="currentTab = 'basic'"
                                :class="currentTab === 'basic' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Basic Info
                                </span>
                            </button>
                            
                            <button type="button" @click="currentTab = 'timeline'"
                                :class="currentTab === 'timeline' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Timeline & Target
                                </span>
                            </button>
                            
                            <button type="button" @click="currentTab = 'optional'"
                                :class="currentTab === 'optional' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                                <span class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                    Optional Details
                                </span>
                            </button>
                        </nav>
                    </div>

                    <div class="p-8">
                        {{-- TAB 1: BASIC INFO --}}
                        <div x-show="currentTab === 'basic'" class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                @if($churches->count() > 1)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                        <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                            <option value="">Select Church</option>
                                            @foreach($churches as $church)
                                                <option value="{{ $church->id }}" {{ old('church_id', $objective->church_id) == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('church_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @else
                                    <input type="hidden" name="church_id" value="{{ $objective->church_id }}">
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                                    <select name="department_id" required 
                                            @change="hasCustomFields = {{ collect($departments)->map(function($d) { return ['id' => $d->id, 'has' => \App\Models\CustomFieldDefinition::where('department_id', $d->id)->active()->count() > 0]; })->toJson() }}[event.target.value]?.has || false"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id', $objective->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Objective Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $objective->name) }}" required 
                                       placeholder="e.g., Bible Distribution Campaign"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <select name="activity_category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Select Category</option>
                                        <option value="Evangelism" {{ old('activity_category', $objective->activity_category) == 'Evangelism' ? 'selected' : '' }}>Evangelism</option>
                                        <option value="Infrastructure" {{ old('activity_category', $objective->activity_category) == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                        <option value="Finance" {{ old('activity_category', $objective->activity_category) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Training" {{ old('activity_category', $objective->activity_category) == 'Training' ? 'selected' : '' }}>Training</option>
                                        <option value="Social" {{ old('activity_category', $objective->activity_category) == 'Social' ? 'selected' : '' }}>Social Services</option>
                                        <option value="Worship" {{ old('activity_category', $objective->activity_category) == 'Worship' ? 'selected' : '' }}>Worship</option>
                                        <option value="Other" {{ old('activity_category', $objective->activity_category) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('activity_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority <span class="text-red-500">*</span></label>
                                    <select name="priority_level" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="low" {{ old('priority_level', $objective->priority_level) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority_level', $objective->priority_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority_level', $objective->priority_level) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('priority_level', $objective->priority_level) == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                    @error('priority_level') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description & Objectives</label>
                                <textarea name="description" rows="4" 
                                          placeholder="What is this objective about? What are the goals?"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('description', $objective->description) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Brief description and what you want to achieve</p>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="currentTab = 'timeline'" 
                                        class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition">
                                    Next: Timeline & Target →
                                </button>
                            </div>
                        </div>

                        {{-- TAB 2: TIMELINE & TARGET --}}
                        <div x-show="currentTab === 'timeline'" class="space-y-6">
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="start_date" value="{{ old('start_date', $objective->start_date?->format('Y-m-d')) }}" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" name="end_date" value="{{ old('end_date', $objective->end_date?->format('Y-m-d')) }}" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">Leave blank if ongoing</p>
                                    @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="planned" {{ old('status', $objective->status) == 'planned' ? 'selected' : '' }}>Planned</option>
                                        <option value="in_progress" {{ old('status', $objective->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $objective->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $objective->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Value <span class="text-red-500">*</span></label>
                                    <input type="number" name="target" value="{{ old('target', $objective->target) }}" required min="0" 
                                           placeholder="e.g., 10000"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('target') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                    <input type="text" name="target_unit" value="{{ old('target_unit', $objective->target_unit) }}" 
                                           placeholder="e.g., Bibles, People"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <p class="text-xs text-gray-500 mt-1">What are you measuring?</p>
                                    @error('target_unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Track Progress</label>
                                    <select name="tracking_frequency" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="daily" {{ old('tracking_frequency', $objective->tracking_frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ old('tracking_frequency', $objective->tracking_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="biweekly" {{ old('tracking_frequency', $objective->tracking_frequency) == 'biweekly' ? 'selected' : '' }}>Bi-Weekly</option>
                                        <option value="monthly" {{ old('tracking_frequency', $objective->tracking_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    </select>
                                    @error('tracking_frequency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Responsible Person</label>
                                <input type="text" name="responsible_person" value="{{ old('responsible_person', $objective->responsible_person) }}" 
                                       placeholder="Name of person leading this activity"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('responsible_person') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex justify-between">
                                <button type="button" @click="currentTab = 'basic'" 
                                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                                    ← Back
                                </button>
                                <div class="flex gap-3">
                                    <button type="button" @click="currentTab = 'optional'" 
                                            class="px-6 py-3 border border-purple-300 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition">
                                        Optional Details →
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                                        ✓ Update Objective
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- TAB 3: OPTIONAL DETAILS --}}
                        <div x-show="currentTab === 'optional'" class="space-y-6">

                            {{-- Budget --}}
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                <h4 class="font-semibold text-gray-900 mb-4">💰 Budget (Optional)</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Budget Estimate (RWF)</label>
                                        <input type="number" name="budget_estimate" value="{{ old('budget_estimate', $objective->budget_estimate) }}" min="0" step="1000" 
                                               placeholder="0"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        @error('budget_estimate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Funding Source</label>
                                        <select name="funding_source" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                            <option value="">Select Source</option>
                                            <option value="Church" {{ old('funding_source', $objective->funding_source) == 'Church' ? 'selected' : '' }}>Church</option>
                                            <option value="Diocese" {{ old('funding_source', $objective->funding_source) == 'Diocese' ? 'selected' : '' }}>Diocese</option>
                                            <option value="Donation" {{ old('funding_source', $objective->funding_source) == 'Donation' ? 'selected' : '' }}>Donation</option>
                                            <option value="Grant" {{ old('funding_source', $objective->funding_source) == 'Grant' ? 'selected' : '' }}>Grant</option>
                                            <option value="Mixed" {{ old('funding_source', $objective->funding_source) == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                                        </select>
                                        @error('funding_source') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Beneficiaries --}}
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                <h4 class="font-semibold text-gray-900 mb-4">👥 Target Beneficiaries (Optional)</h4>
                                <textarea name="target_beneficiaries" rows="2" 
                                          placeholder="Who will benefit? e.g., Youth, Families, Community members"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('target_beneficiaries', $objective->target_beneficiaries) }}</textarea>
                                @error('target_beneficiaries') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Detailed Objectives --}}
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                <h4 class="font-semibold text-gray-900 mb-4">Detailed Objectives/Expected Outcomes (Optional)</h4>
                                <textarea name="objectives" rows="2" 
                                          placeholder="Detailed breakdown of objectives..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 mb-2">{{ old('objectives', $objective->objectives) }}</textarea>
                                <textarea name="expected_outcomes" rows="2" 
                                          placeholder="Expected outcomes..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('expected_outcomes', $objective->expected_outcomes) }}</textarea>
                            </div>

                            {{-- Existing Documents --}}
                            @if($objective->documents && count($objective->documents) > 0)
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                <h4 class="font-semibold text-gray-900 mb-4">📎 Existing Documents</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach($objective->documents as $index => $doc)
                                        <div class="flex items-center justify-between bg-white p-3 rounded border">
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-sm text-purple-600 hover:underline flex-1 truncate">
                                                📄 {{ $doc->file_name ?? 'Document ' . ($index + 1) }}
                                            </a>
                                            <label class="ml-2">
                                                <input type="checkbox" name="remove_documents[]" value="{{ $doc->id }}" class="rounded">
                                                <span class="text-xs text-red-600 ml-1">Remove</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Upload New Documents --}}
                            <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                <h4 class="font-semibold text-gray-900 mb-4">📎 Upload New Documents (Optional)</h4>
                                <input type="file" name="documents[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <p class="text-xs text-gray-500 mt-2">Upload supporting documents (max 5MB each)</p>
                            </div>

                            {{-- Custom Fields --}}
                            <div x-show="hasCustomFields" class="border border-purple-200 rounded-lg p-6 bg-purple-50">
                                <h4 class="font-semibold text-gray-900 mb-4">⚙️ Department-Specific Fields</h4>
                                
                                @foreach($departments as $department)
                                    @php
                                        $deptFieldsCount = \App\Models\CustomFieldDefinition::where('department_id', $department->id)
                                            ->active()
                                            ->count();
                                    @endphp
                                    
                                    @if($deptFieldsCount > 0)
                                        <div x-show="document.querySelector('[name=department_id]')?.value == '{{ $department->id }}'" style="display: {{ $objective->department_id == $department->id ? 'block' : 'none' }};">
                                            <p class="text-sm text-gray-600 mb-4">Custom fields for {{ $department->name }}:</p>
                                            <x-custom-fields-form :departmentId="$department->id" :activityId="$objective->id" />
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="flex justify-between pt-4">
                                <button type="button" @click="currentTab = 'timeline'" 
                                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 transition">
                                    ← Back
                                </button>
                                <button type="submit" 
                                        class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition">
                                    ✓ Update Objective
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
