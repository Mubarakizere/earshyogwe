<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Worker Profile
            </h2>
            <div class="flex gap-2">
                @can('edit worker')
                <a href="{{ route('workers.edit', $worker) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                    Edit Profile
                </a>
                @endcan
                <a href="{{ route('workers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg shadow transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Worker Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-8 mb-6 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-24 w-24 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white uppercase font-bold text-3xl border-4 border-white border-opacity-30">
                        {{ substr($worker->first_name, 0, 1) }}{{ substr($worker->last_name, 0, 1) }}
                    </div>
                    <div class="ml-6">
                        <h3 class="text-3xl font-bold">{{ $worker->full_name }}</h3>
                        <p class="text-xl text-blue-100 mt-1">{{ $worker->job_title ?? 'N/A' }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm font-medium">
                                {{ ucfirst($worker->gender ?? 'N/A') }}
                            </span>
                            @if($worker->status === 'active')
                                <span class="px-3 py-1 bg-green-500 rounded-full text-sm font-bold">Active</span>
                            @elseif($worker->status === 'retired')
                                <span class="px-3 py-1 bg-gray-400 rounded-full text-sm font-bold">Retired</span>
                            @else
                                <span class="px-3 py-1 bg-red-500 rounded-full text-sm font-bold">Terminated</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Personal Information</h4>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $worker->full_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($worker->gender ?? 'N/A') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="text-sm text-gray-900">{{ $worker->birth_date ? $worker->birth_date->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">National ID</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $worker->national_id ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Education</dt>
                                <dd class="text-sm text-gray-900">{{ $worker->education_qualification ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Contact Information</h4>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="mailto:{{ $worker->email }}" class="text-blue-600 hover:underline">{{ $worker->email }}</a>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="tel:{{ $worker->phone }}" class="text-blue-600 hover:underline">{{ $worker->phone ?? 'N/A' }}</a>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">District</dt>
                                <dd class="text-sm text-gray-900">{{ $worker->district ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Sector</dt>
                                <dd class="text-sm text-gray-900">{{ $worker->sector ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Employment Details</h4>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $worker->job_title ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Institution</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($worker->institution)
                                        <div>{{ $worker->institution->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $worker->institution->type_name }}</div>
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Employment Date</dt>
                                <dd class="text-sm text-gray-900">{{ $worker->employment_date ? $worker->employment_date->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="text-sm">
                                    @if($worker->status === 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Active</span>
                                    @elseif($worker->status === 'retired')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-semibold">Retired</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">Terminated</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h4 class="text-lg font-semibold text-gray-800">Documents</h4>
                    </div>
                    <div class="p-6">
                        @if($worker->documents->count() > 0)
                            <div class="grid grid-cols-1 gap-3">
                                @foreach($worker->documents as $document)
                                    @php
                                        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                                        $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : ($extension === 'pdf' ? 'pdf' : 'other');
                                        $url = route('worker-documents.download', $document);
                                    @endphp
                                    
                                    @if($type === 'other')
                                        <a href="{{ $url }}" download class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition bg-gray-50 hover:bg-white group">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                <div class="overflow-hidden flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->document_name }}</p>
                                                    <p class="text-xs text-blue-500">Download File</p>
                                                </div>
                                            </div>
                                        </a>
                                    @else
                                        <button type="button" 
                                            @click="$dispatch('open-document-modal', { url: '{{ $url }}', type: '{{ $type }}' })"
                                            class="block w-full text-left p-4 border border-gray-200 rounded-lg hover:shadow-md transition bg-gray-50 hover:bg-white group focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <div class="flex items-center gap-3">
                                                <svg class="w-8 h-8 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                <div class="overflow-hidden flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $document->document_name }}</p>
                                                    <p class="text-xs text-gray-500">Click to View</p>
                                                </div>
                                            </div>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No documents uploaded</p>
                            </div>
                        @endif
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
