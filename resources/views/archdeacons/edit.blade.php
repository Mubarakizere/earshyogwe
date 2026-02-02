<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('Edit Archdeacon Assignments') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Assign parishes to {{ $archdeacon->name }}</p>
            </div>
            <a href="{{ route('archdeacons.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Archdeacon Info Card -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-100 mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        <img class="h-20 w-20 rounded-full border-2 border-purple-200 object-cover" src="{{ $archdeacon->profile_photo_url }}" alt="{{ $archdeacon->name }}">
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $archdeacon->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $archdeacon->email }}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-purple-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                    Archdeacon
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-100">
                <form method="POST" action="{{ route('archdeacons.update', $archdeacon->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Parish Assignments</h3>
                            <p class="text-sm text-gray-500">Select the parishes that this archdeacon will supervise.</p>
                        </div>

                        @if($errors->any())
                            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <ul class="mt-2 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Church Selection -->
                        <div class="space-y-3 max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            @if($allChurches->count() > 0)
                                @foreach($allChurches as $church)
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition-all">
                                        <input 
                                            type="checkbox" 
                                            name="church_ids[]" 
                                            value="{{ $church->id }}"
                                            {{ in_array($church->id, $assignedChurchIds) ? 'checked' : '' }}
                                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                                        >
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-sm font-medium text-gray-900">{{ $church->name }}</span>
                                                    <p class="text-xs text-gray-500">{{ $church->location }}</p>
                                                </div>
                                                <div class="text-right">
                                                    @if($church->pastor)
                                                        <span class="text-xs text-gray-600">Pastor: {{ $church->pastor->name }}</span>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">No pastor assigned</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <p class="mt-2 text-sm text-gray-500">No parishes available</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-blue-700">
                                        <strong>Tip:</strong> You can assign multiple parishes to this archdeacon. Previously assigned parishes will be removed if unchecked.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-3 border-t border-gray-200">
                        <a href="{{ route('archdeacons.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Assignments
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
