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
                            <ul class="space-y-3">
                                @foreach($worker->documents as $document)
                                    <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $document->document_name }}</p>
                                                <p class="text-xs text-gray-500">Uploaded {{ $document->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('worker-documents.download', $document) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
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
</x-app-layout>
