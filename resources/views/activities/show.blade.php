<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Activity Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $activity->name }}</h3>
                            <div class="text-sm text-gray-500 flex items-center gap-4">
                                <span class="bg-purple-100 text-purple-800 py-1 px-3 rounded-full font-medium">{{ $activity->department->name ?? 'No Department' }}</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $activity->church->name }}
                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                             @php
                                $statusColors = [
                                    'planned' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabel = ucfirst(str_replace('_', ' ', $activity->status));
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$activity->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabel }}
                            </span>
                             <p class="text-sm text-gray-400 mt-2">ID: #{{ $activity->id }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <!-- Key Details -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                             <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Dates & Responsible</h4>
                             <div class="space-y-3">
                                <div>
                                    <span class="block text-xs text-gray-400">Start Date</span>
                                    <span class="text-gray-900 font-medium">{{ $activity->start_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-400">End Date</span>
                                    <span class="text-gray-900 font-medium">{{ $activity->end_date ? $activity->end_date->format('M d, Y') : 'Ongoing' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs text-gray-400">Responsible Person</span>
                                    <span class="text-gray-900 font-medium">{{ $activity->responsible_person ?? 'N/A' }}</span>
                                </div>
                             </div>
                        </div>

                        <!-- Progress -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Performance</h4>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-500">Progress</span>
                                        <span class="text-gray-900 font-bold">{{ $activity->target > 0 ? round(($activity->current_progress / $activity->target) * 100) : 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $activity->target > 0 ? min(round(($activity->current_progress / $activity->target) * 100), 100) : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <div>
                                        <span class="block text-xs text-gray-400">Current</span>
                                        <span class="text-gray-900 font-bold text-lg">{{ number_format($activity->current_progress) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-xs text-gray-400">Target</span>
                                        <span class="text-gray-900 font-bold text-lg">{{ number_format($activity->target) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                             <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Description</h4>
                             <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $activity->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Documents</h4>
                        @if($activity->documents && $activity->documents->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($activity->documents as $doc)
                                    @php
                                        $extension = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                                        $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : ($extension === 'pdf' ? 'pdf' : 'other');
                                        $url = Storage::url($doc->file_path);
                                    @endphp
                                    
                                    @if($type === 'other')
                                        <a href="{{ $url }}" download class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition bg-gray-50 hover:bg-white group">
                                            <div class="flex items-center gap-3">
                                                 <svg class="w-8 h-8 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                 <div class="overflow-hidden">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ basename($doc->file_path) }}</p>
                                                    <p class="text-xs text-blue-500">Download File</p>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <button type="button" 
                                            @click="$dispatch('open-document-modal', { url: '{{ $url }}', type: '{{ $type }}' })"
                                            class="block w-full text-left p-4 border border-gray-200 rounded-lg hover:shadow-md transition bg-gray-50 hover:bg-white group focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-8 h-8 text-gray-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <div class="overflow-hidden">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ basename($doc->file_path) }}</p>
                                                    <p class="text-xs text-gray-500">Click to View</p>
                                                </div>
                                            </div>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">No documents attached.</p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6">
                        <a href="{{ route('activities.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Back to List
                        </a>
                        
                        @can('edit activities')
                        <a href="{{ route('activities.edit', $activity) }}" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg shadow-sm transition">
                            Edit Activity
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Viewer Modal -->
    <div x-data="{ open: false, url: '', type: '' }" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-on:open-document-modal.window="
            open = true; 
            url = $event.detail.url;
            type = $event.detail.type;
         ">
        <div class="flex items-center justify-center min-h-screen text-center sm:p-0">
            <div x-show="open" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-900 opacity-95"></div>
            </div>
            
            <button @click="open = false" class="fixed top-4 right-4 text-white hover:text-gray-300 z-[60] focus:outline-none p-2 bg-black bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div x-show="open" 
                 class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full h-full sm:h-auto sm:w-full sm:max-w-5xl max-h-[95vh] flex flex-col">
                <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center shrink-0">
                    <h3 class="text-lg font-medium text-gray-900">Document Viewer</h3>
                    <a :href="url" download class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download
                    </a>
                </div>
                
                <div class="overflow-auto p-2 sm:p-4 flex-1 bg-gray-50 flex items-center justify-center min-h-[50vh]">
                    <template x-if="type === 'image'">
                        <img :src="url" class="max-w-full max-h-[85vh] object-contain shadow-sm rounded">
                    </template>
                    <template x-if="type === 'pdf'">
                        <iframe :src="url" class="w-full h-[85vh] border-0 rounded shadow-sm bg-white"></iframe>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
