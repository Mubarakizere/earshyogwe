<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Add New Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
                
                <form method="POST" action="{{ route('members.store') }}" x-data="memberFormData()">
                    @csrf

                    <!-- Standard Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        
                        <!-- Church -->
                        <div>
                            <x-input-label for="church_id" :value="__('Parish')" />
                            <select id="church_id" name="church_id" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}">{{ $church->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('church_id')" class="mt-2" />
                        </div>

                        <!-- Chapel -->
                        <div>
                            <x-input-label for="chapel" :value="__('Chapel (Optional)')" />
                            <x-text-input id="chapel" class="block mt-1 w-full" type="text" name="chapel" :value="old('chapel')" placeholder="e.g. St. Mary's Chapel" />
                            <x-input-error :messages="$errors->get('chapel')" class="mt-2" />
                        </div>

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Full Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Sex -->
                        <div>
                            <x-input-label for="sex" :value="__('Sex')" />
                            <select id="sex" name="sex" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                        </div>

                        <!-- DOB -->
                        <div>
                            <x-input-label for="dob" :value="__('Date of Birth')" />
                            <x-text-input id="dob" class="block mt-1 w-full" type="date" name="dob" :value="old('dob')" x-model="dob" @change="checkIfChild()" />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>

                        <!-- Marital Status -->
                        <div>
                            <x-input-label for="marital_status" :value="__('Marital Status')" />
                            <select id="marital_status" name="marital_status" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
                        </div>

                        <!-- Parental Status (Optional) -->
                        <div>
                            <x-input-label for="parental_status" :value="__('Parental Status (Optional)')" />
                            <select id="parental_status" name="parental_status" x-model="parentalStatus" @change="checkIfChild()" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="">Select Status</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="Living with both parents">Living with both parents</option>
                                <option value="Living with one parent">Living with one parent</option>
                                <option value="Orphan">Orphan</option>
                                <option value="Under guardian/Caregiver">Under guardian/Caregiver</option>
                            </select>
                             <x-input-error :messages="$errors->get('parental_status')" class="mt-2" />
                        </div>

                        <!-- Baptism Status -->
                        <div>
                            <x-input-label for="baptism_status" :value="__('Baptism Status')" />
                            <select id="baptism_status" name="baptism_status" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="Baptized">Baptized</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="None">None</option>
                            </select>
                            <x-input-error :messages="$errors->get('baptism_status')" class="mt-2" />
                        </div>

                        <!-- Parent Names (shown for children) -->
                        <div x-show="showParentNames" x-cloak class="col-span-1 md:col-span-2">
                            <x-input-label for="parent_names" :value="__('Parent/Guardian Names')" />
                            <x-text-input id="parent_names" class="block mt-1 w-full" type="text" name="parent_names" :value="old('parent_names')" placeholder="e.g. John Doe & Jane Doe" />
                            <p class="text-xs text-gray-500 mt-1">Please enter the names of the member's parents or guardians</p>
                            <x-input-error :messages="$errors->get('parent_names')" class="mt-2" />
                        </div>

                         <!-- Education Level -->
                         <div>
                            <x-input-label for="education_level" :value="__('Education Level')" />
                            <select id="education_level" name="education_level" class="block mt-1 w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 rounded-lg shadow-sm">
                                <option value="">Select Level</option>
                                <option value="Primary">Primary</option>
                                <option value="Secondary">Secondary</option>
                                <option value="University">University</option>
                                <option value="None">None</option>
                                <option value="Other">Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('education_level')" class="mt-2" />
                        </div>

                        <!-- Disability -->
                        <div>
                            <x-input-label for="disability" :value="__('Disability (Optional)')" />
                            <x-text-input id="disability" class="block mt-1 w-full" type="text" name="disability" :value="old('disability')" placeholder="Leave empty if none, or describe disability" />
                            <x-input-error :messages="$errors->get('disability')" class="mt-2" />
                        </div>

                        <!-- Church Groups (Multiple Selection) -->
                        <div class="col-span-1 md:col-span-2">
                            <x-input-label for="church_groups" :value="__('Church Groups / Fellowships')" />
                            <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                @foreach($churchGroups as $group)
                                    <label class="flex items-center space-x-2 cursor-pointer hover:text-brand-600">
                                        <input type="checkbox" name="church_groups[]" value="{{ $group->id }}" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                        <span class="text-sm">{{ $group->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                             <x-input-error :messages="$errors->get('church_groups')" class="mt-2" />
                        </div>
                    </div>

                    <hr class="my-6 border-gray-100">

                    <!-- Dynamic Extra Attributes Section -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Additional Details</h3>
                            <button type="button" @click="addField()" class="text-sm font-semibold text-brand-600 hover:text-brand-800 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                Add Custom Field
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase">Field Name</label>
                                        <input type="text" x-model="field.key" placeholder="e.g. Occupation" class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-brand-500 focus:ring-brand-500">
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase">Value</label>
                                        <input type="text" 
                                               :name="'extra_attributes[' + (field.key || 'temp_' + index) + ']'" 
                                               x-model="field.value" 
                                               placeholder="e.g. Engineer" 
                                               class="block w-full mt-1 text-sm border-gray-300 rounded-md focus:border-brand-500 focus:ring-brand-500">
                                    </div>
                                    <button type="button" @click="removeField(index)" class="mt-6 text-gray-400 hover:text-red-500">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </template>
                             <template x-if="fields.length === 0">
                                <p class="text-sm text-gray-400 italic">No additional details added yet.</p>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <x-primary-button>
                            {{ __('Save Member') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function memberFormData() {
            return {
                fields: [],
                dob: '',
                parentalStatus: '',
                showParentNames: false,
                
                addField() {
                    this.fields.push({ key: '', value: '' });
                },
                removeField(index) {
                    this.fields.splice(index, 1);
                },
                checkIfChild() {
                    // Check if DOB indicates a child (under 18)
                    let isChild = false;
                    if (this.dob) {
                        const birthDate = new Date(this.dob);
                        const today = new Date();
                        const age = today.getFullYear() - birthDate.getFullYear();
                        const monthDiff = today.getMonth() - birthDate.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            isChild = (age - 1) < 18;
                        } else {
                            isChild = age < 18;
                        }
                    }
                    
                    // Also show if parental status indicates a child/dependent
                    const childStatuses = ['Living with both parents', 'Living with one parent', 'Orphan', 'Under guardian/Caregiver'];
                    const hasChildStatus = childStatuses.includes(this.parentalStatus);
                    
                    this.showParentNames = isChild || hasChildStatus;
                }
            };
        }
    </script>
</x-app-layout>

