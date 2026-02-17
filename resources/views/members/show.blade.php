<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Member Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Header Actions -->
                    <div class="flex justify-between items-start mb-8">
                        <div class="flex items-center space-x-4">
                             <div class="h-16 w-16 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-2xl font-bold">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold text-gray-900">{{ $member->name }}</h3>
                                <p class="text-lg text-gray-600">{{ $member->church->name }}</p>
                                @if($member->member_id)
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 font-mono tracking-wide mt-1">
                                        ID: {{ $member->member_id }}
                                    </span>
                                @endif
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-800 border-green-200',
                                        'inactive' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'deceased' => 'bg-gray-200 text-gray-800 border-gray-300'
                                    ];
                                    $status = $member->status ?? 'active';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusColors[$status] }} mt-2">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Edit Profile
                            </a>
                            <a href="{{ route('members.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Demographics -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800 pb-2 border-b">Personal Information</h4>
                            <dl class="space-y-4">
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="text-sm font-bold text-gray-900 col-span-2">{{ $member->sex }}</dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        {{ $member->dob ? $member->dob->format('M d, Y') : 'N/A' }} 
                                        @if($member->age)
                                            <span class="text-gray-500 text-xs">({{ $member->age }} years)</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Marital Status</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $member->marital_status }}</dd>
                                </div>
                                 <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-gray-500">Parental Status</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $member->parental_status }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Church Status -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="text-lg font-semibold mb-4 text-blue-800 pb-2 border-b border-blue-200">Church Involvement</h4>
                            <dl class="space-y-4">
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-blue-600">Baptism Status</dt>
                                    <dd class="text-sm font-bold text-gray-900 col-span-2">
                                        <span class="px-2 py-1 rounded-full text-xs bg-white text-blue-800 border border-blue-100">
                                            {{ $member->baptism_status }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-blue-600">Church Groups</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">
                                        @if($member->churchGroups && $member->churchGroups->count() > 0)
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($member->churchGroups as $group)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                        {{ $group->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">None</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="grid grid-cols-3">
                                    <dt class="text-sm font-medium text-blue-600">Education</dt>
                                    <dd class="text-sm text-gray-900 col-span-2">{{ $member->education_level ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Status Information (if inactive or deceased) -->
                    @if($member->status === 'inactive')
                        <div class="mb-8 bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                            <h4 class="text-lg font-semibold mb-4 text-yellow-800 pb-2 border-b border-yellow-200">Inactive Status</h4>
                            <dl class="space-y-3">
                                @if($member->inactive_date)
                                    <div class="grid grid-cols-3">
                                        <dt class="text-sm font-medium text-yellow-700">Inactive Since</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $member->inactive_date->format('M d, Y') }}</dd>
                                    </div>
                                @endif
                                @if($member->inactive_reason)
                                    <div class="grid grid-cols-3">
                                        <dt class="text-sm font-medium text-yellow-700">Reason</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $member->inactive_reason }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    @if($member->status === 'deceased')
                        <div class="mb-8 bg-gray-100 p-6 rounded-lg border border-gray-300">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800 pb-2 border-b border-gray-300">Deceased Information</h4>
                            <dl class="space-y-3">
                                @if($member->deceased_date)
                                    <div class="grid grid-cols-3">
                                        <dt class="text-sm font-medium text-gray-700">Date of Death</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $member->deceased_date->format('M d, Y') }}</dd>
                                    </div>
                                @endif
                                @if($member->deceased_cause)
                                    <div class="grid grid-cols-3">
                                        <dt class="text-sm font-medium text-gray-700">Cause/Notes</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $member->deceased_cause }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    @endif

                    <!-- Additional Info -->
                    @if(!empty($member->extra_attributes))
                    <div class="mt-6 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="text-lg font-semibold mb-4 text-gray-800">Additional Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($member->extra_attributes as $key => $value)
                                <div class="bg-white p-3 rounded border">
                                    <span class="block text-xs font-semibold text-gray-500 uppercase">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                    <span class="block text-sm text-gray-900 mt-1">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
