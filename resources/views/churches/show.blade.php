<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __($church->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $church->name }}</h3>
                            <div class="flex items-center text-gray-500 space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $church->location }}
                                </span>
                                @if($church->email)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ $church->email }}
                                    </span>
                                @endif
                                @if($church->phone)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        {{ $church->phone }}
                                    </span>
                                @endif
                            </div>
                        </div>
                         <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $church->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $church->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Leadership -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Leadership</h4>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="text-gray-600">Pastor</span>
                                    <span class="font-medium text-gray-900">{{ $church->pastor->name ?? 'Unassigned' }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                                    <span class="text-gray-600">Archdeacon</span>
                                    <span class="font-medium text-gray-900">{{ $church->archid->name ?? 'Unassigned' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Statistics</h4>
                             <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-2xl font-bold text-gray-900">{{ $church->users->count() }}</span>
                                    <span class="text-xs text-gray-500">System Users</span>
                                </div>
                                <div>
                                    <span class="block text-2xl font-bold text-gray-900">{{ $church->members_count ?? 0 }}</span> <!-- Assuming count exists or load it -->
                                    <span class="text-xs text-gray-500">Members</span>
                                </div>
                             </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                        <a href="{{ route('churches.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Back to List
                        </a>
                        
                        @can('edit church')
                        <a href="{{ route('churches.edit', $church) }}" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition">
                            Edit Church
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
