<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Member</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('members.update', $member) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Church & Basic Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($churches->count() > 1)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                    <select name="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}" {{ $member->church_id == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="church_id" value="{{ $member->church_id }}">
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ $member->name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <!-- Personal Details -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                                <select name="sex" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="Male" {{ $member->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $member->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" name="dob" value="{{ $member->dob ? $member->dob->format('Y-m-d') : '' }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status <span class="text-red-500">*</span></label>
                                <select name="marital_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="Single" {{ $member->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $member->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ $member->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ $member->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Church Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Baptism Status <span class="text-red-500">*</span></label>
                                <select name="baptism_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="None" {{ $member->baptism_status == 'None' ? 'selected' : '' }}>None</option>
                                    <option value="Baptized" {{ $member->baptism_status == 'Baptized' ? 'selected' : '' }}>Baptized</option>
                                    <option value="Confirmed" {{ $member->baptism_status == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                </select>
                            </div>
                             <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Parental Status <span class="text-red-500">*</span></label>
                                <select name="parental_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                    <option value="Living with both parents" {{ $member->parental_status == 'Living with both parents' ? 'selected' : '' }}>Living with both parents</option>
                                    <option value="Living with one parent" {{ $member->parental_status == 'Living with one parent' ? 'selected' : '' }}>Living with one parent</option>
                                    <option value="Orphan" {{ $member->parental_status == 'Orphan' ? 'selected' : '' }}>Orphan</option>
                                    <option value="Under guardian/Caregiver" {{ $member->parental_status == 'Under guardian/Caregiver' ? 'selected' : '' }}>Under guardian/Caregiver</option>
                                </select>
                            </div>
                        </div>
                        
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Church Group</label>
                                <input type="text" name="church_group" value="{{ $member->church_group }}" placeholder="e.g. Choir" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Education Level</label>
                                <input type="text" name="education_level" value="{{ $member->education_level }}" placeholder="e.g. High School" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                             <!-- Delete Button Trigger -->
                            <button type="button" onclick="if(confirm('Are you sure you want to permanently delete this member?')) document.getElementById('delete-form').submit();" class="text-red-600 hover:text-red-800 font-medium">
                                Delete Member
                            </button>
                            
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('members.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Update Member</button>
                            </div>
                        </div>
                    </div>
                </form>
                
                <form id="delete-form" action="{{ route('members.destroy', $member) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
