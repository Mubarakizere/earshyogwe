<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Member Registry') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('members.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                     <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
                <a href="{{ route('members.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Member
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ deleteRoute: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pro Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Gradients: Blue-Indigo,  -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80 font-medium">Total Members</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['total'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-20 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-300">
                     <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80 font-medium">Men</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['male'] ?? 0) }}</p>
                        </div>
                         <div class="p-3 bg-white bg-opacity-20 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-300">
                     <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80 font-medium">Women</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['female'] ?? 0) }}</p>
                        </div>
                         <div class="p-3 bg-white bg-opacity-20 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-teal-500 to-emerald-500 rounded-lg shadow-lg p-6 text-white transform hover:scale-105 transition duration-300">
                     <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm opacity-80 font-medium">Baptized</p>
                            <p class="text-3xl font-bold mt-1">{{ number_format($stats['baptized'] ?? 0) }}</p>
                        </div>
                         <div class="p-3 bg-white bg-opacity-20 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-100">
                <form action="{{ route('members.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <!-- Search Input -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Role-Based Church Filter -->
                     @if(isset($churches) && $churches->isNotEmpty())
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Church</label>
                        <select name="church_id" class="w-full py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Churches</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                     <div class="hidden md:block md:col-span-3"></div>
                    @endif

                    <!-- Status Filter -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                        <select name="status" class="w-full py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All Statuses</option>
                            <option value="Single" {{ request('status') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ request('status') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ request('status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ request('status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>

                     <!-- Gender Filter -->
                     <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Gender</label>
                        <select name="sex" class="w-full py-2 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="">All</option>
                            <option value="Male" {{ request('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ request('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <!-- ... Table ... -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Church</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demographics</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($members as $member)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                             <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs mr-3">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('members.show', $member) }}" class="text-sm font-bold text-gray-900 hover:text-indigo-600">
                                                    {{ $member->name }}
                                                </a>
                                                <div class="text-xs text-gray-500">{{ $member->sex }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $member->church->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $member->age ? $member->age . ' yrs' : 'N/A' }}</div>
                                        <div class="text-xs text-gray-500">{{ $member->education_level ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-xs text-gray-600">{{ $member->marital_status }}</span>
                                            <span class="text-xs {{ $member->baptism_status == 'Baptized' || $member->baptism_status == 'Confirmed' ? 'text-green-600' : 'text-gray-400' }}">
                                                {{ $member->baptism_status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $member->church_group ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <!-- Actions -->
                                        <a href="{{ route('members.show', $member) }}" class="text-gray-400 hover:text-blue-600">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('members.edit', $member) }}" class="text-gray-400 hover:text-indigo-600">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button 
                                            type="button" 
                                            x-on:click="deleteRoute = '{{ route('members.destroy', $member) }}'; $dispatch('open-modal', 'confirm-member-deletion')"
                                            class="text-gray-400 hover:text-red-600">
                                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        <x-empty-state 
                                            title="No members found" 
                                            message="Add members to start populating your registry."
                                            action="Add Member" 
                                            url="{{ route('members.create') }}"
                                            icon="users"
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $members->links() }}
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <x-modal name="confirm-member-deletion" focusable>
                 <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Delete Member
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Are you sure you want to delete this member? This action cannot be undone.
                    </p>

                    <div class="mt-6 flex justify-end">
                        <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </button>

                        <form :action="deleteRoute" method="POST" class="ml-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Member
                            </button>
                        </form>
                    </div>
                </div>
            </x-modal>

        </div>
    </div>
</x-app-layout>
