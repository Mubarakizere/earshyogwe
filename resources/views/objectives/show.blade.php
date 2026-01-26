<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Objective Details') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('objectives.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    &larr; Back to Objectives
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        showDeleteModal: false,
        approveModalOpen: false,
        docModalOpen: false,
        docUrl: '',
        docType: ''
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- MAIN CONTENT (Left Column) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Objective Header & Description --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $objective->department->name ?? 'General' }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $objective->church->name ?? 'Diocese-wide' }}</span>
                                    </div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $objective->name }}</h1>
                                    @if($objective->objectives)
                                        <div class="text-sm text-gray-500 mt-1 italic">{{ $objective->objectives }}</div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    @can('submit objective reports')
                                    <a href="{{ route('objectives.report.create', $objective) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                        Submit Report
                                    </a>
                                    @endcan

                                    @can('edit objectives')
                                        <a href="{{ route('objectives.edit', $objective) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                                            Edit
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <div class="prose max-w-none text-gray-600">
                                {{ $objective->description ?? 'No description available.' }}
                            </div>

                            @if($objective->expected_outcomes)
                                <h4 class="text-md font-medium text-gray-900 mt-6 mb-2">Expected Outcomes</h4>
                                <div class="prose max-w-none text-gray-600">
                                    {{ $objective->expected_outcomes }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Documents --}}
                    @if($objective->documents && $objective->documents->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Attachments</h3>
                        </div>
                        <div class="p-6">
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($objective->documents as $doc)
                                        @php
                                            $extension = strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION));
                                            $type = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) ? 'image' : ($extension === 'pdf' ? 'pdf' : 'other');
                                            $url = Storage::url($doc->file_path);
                                        @endphp
                                        <li class="col-span-1 flex shadow-sm rounded-md">
                                            <div class="flex-shrink-0 flex items-center justify-center w-16 bg-gray-100 text-gray-400 text-xs font-bold uppercase rounded-l-md">
                                                {{ $extension }}
                                            </div>
                                            <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                                <div class="flex-1 px-4 py-2 text-sm truncate">
                                                    <span class="text-gray-900 font-medium hover:text-gray-600 truncate block" title="{{ basename($doc->file_path) }}">
                                                        {{ basename($doc->file_path) }}
                                                    </span>
                                                </div>
                                                <div class="flex-shrink-0 pr-2">
                                                    @if($type !== 'other')
                                                        <button 
                                                            @click="$dispatch('open-document-modal', { url: '{{ $url }}', type: '{{ $type }}' })"
                                                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            <span class="sr-only">View</span>
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        </button>
                                                    @else
                                                        <a href="{{ $url }}" download class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                        </div>
                    </div>
                     @endif

                    {{-- Objective Reports List --}}
                    @if($objective->reports && $objective->reports->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Reports Submitted</h3>
                            </div>
                            <div class="divide-y divide-gray-200">
                                @foreach($objective->reports as $report)
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-purple-600">
                                                    Report Date: {{ \Carbon\Carbon::parse($report->report_date)->format('M d, Y') }}
                                                </h4>
                                                <p class="text-xs text-gray-500">Submitted by {{ $report->responsible_person }}</p>
                                            </div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                {{ ucfirst($report->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                            <div>
                                                <h5 class="text-xs font-semibold text-gray-500 uppercase">Activities Done</h5>
                                                <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">{{ $report->activities_description }}</p>
                                            </div>
                                            <div>
                                                <h5 class="text-xs font-semibold text-gray-500 uppercase">Results / Outcome</h5>
                                                <p class="text-sm text-gray-700 mt-1 whitespace-pre-wrap">{{ $report->results_outcome }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-6 mt-4 pt-4 border-t border-gray-50">
                                            <div>
                                                <span class="text-xs font-semibold text-gray-500 uppercase block">Indicator</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($report->quantity) }} {{ $objective->target_unit }}</span>
                                            </div>
                                            
                                            @if($report->budget_spent > 0)
                                            <div>
                                                <span class="text-xs font-semibold text-gray-500 uppercase block">Budget Spent</span>
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($report->budget_spent) }} RWF</span>
                                            </div>
                                            @endif

                                            @if($report->location)
                                            <div>
                                                <span class="text-xs font-semibold text-gray-500 uppercase block">Location</span>
                                                <span class="text-sm text-gray-700">{{ $report->location }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        @if($report->documents && $report->documents->count() > 0)
                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <h6 class="text-xs font-semibold text-gray-500 uppercase mb-2">Attachments</h6>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($report->documents as $doc)
                                                    @php
                                                        $url = Storage::url($doc->file_path);
                                                        $isViewable = in_array($doc->file_type, ['image', 'pdf']);
                                                    @endphp
                                                    
                                                    @if($isViewable)
                                                        <button type="button" 
                                                            @click="$dispatch('open-document-modal', { url: '{{ $url }}', type: '{{ $doc->file_type }}' })"
                                                            class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 hover:bg-purple-100 hover:text-purple-700 border border-gray-200 transition-colors">
                                                            @if($doc->file_type === 'image')
                                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            @else
                                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                            @endif
                                                            {{ $doc->file_name }}
                                                        </button>
                                                    @else
                                                        <a href="{{ $url }}" download class="inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 hover:bg-purple-100 hover:text-purple-700 border border-gray-200 transition-colors">
                                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            {{ $doc->file_name }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No reports yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by submitting the first report for this objective.</p>
                            <div class="mt-6">
                                @can('submit objective reports')
                                <a href="{{ route('objectives.report.create', $objective) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Submit Report
                                </a>
                                @endcan
                            </div>
                        </div>
                    @endif

                </div>

                {{-- SIDEBAR (Right Column) --}}
                <div class="space-y-6">
                    
                    {{-- Status Card --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Status</h3>
                            <div class="flex items-center justify-between mb-6">
                                @php
                                    $statusClasses = [
                                        'planned' => 'bg-gray-100 text-gray-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses[$objective->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $objective->status)) }}
                                </span>
                            </div>

                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Start Date</span>
                                    <span class="block text-base font-semibold text-gray-900">{{ $objective->start_date->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">End Date</span>
                                    <span class="block text-base font-semibold text-gray-900">{{ $objective->end_date ? $objective->end_date->format('M d, Y') : 'Ongoing' }}</span>
                                </div>
                                @if($objective->budget_estimate)
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Estimated Budget</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="block text-base font-semibold text-gray-900">{{ number_format($objective->budget_estimate) }}</span>
                                        <span class="text-xs text-gray-500">RWF</span>
                                    </div>
                                </div>
                                @endif
                                @if($objective->funding_source)
                                <div>
                                    <span class="block text-sm font-medium text-gray-500">Funding Source</span>
                                    <span class="block text-base font-semibold text-gray-900">{{ $objective->funding_source }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Progress Card --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Progress</h3>
                            
                            <div class="mb-2 flex justify-between items-end">
                                <span class="text-3xl font-bold text-gray-900">{{ number_format($objective->progress_percentage) }}%</span>
                                <span class="text-sm text-gray-500 mb-1">Target: {{ number_format($objective->target) }} {{ $objective->target_unit }}</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ min(100, $objective->progress_percentage) }}%"></div>
                            </div>
                            
                            <div class="text-right text-sm text-gray-500">
                                Based on {{ $objective->reports->count() }} reports
                            </div>

                        </div>
                    </div>

                    {{-- Approval Actions --}}
                    @if($objective->approval_status === 'pending' && auth()->user()->can('approve objectives'))
                        <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg border border-yellow-200">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-yellow-800 mb-2">Needs Approval</h3>
                                <p class="text-sm text-yellow-700 mb-4">This objective is pending high-level approval.</p>
                                <div class="flex space-x-3">
                                    <button @click="$dispatch('open-approve-modal')" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none">
                                        Approve
                                    </button>
                                    <form action="{{ route('objectives.reject', $objective) }}" method="POST" onsubmit="return confirm('Reject this objective?');" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-yellow-300 shadow-sm text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Admin Actions --}}
                    @can('delete objectives')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <button @click="showDeleteModal = true" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none">
                                Delete Objective
                            </button>
                        </div>
                    @endcan

                </div>

            </div>
        </div>

        {{-- MODALS --}}

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteModal" style="display: none;" 
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="showDeleteModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Delete Objective</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Are you sure you want to delete this objective? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('objectives.destroy', $objective) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                        </form>
                        <button type="button" @click="showDeleteModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Approval Modal --}}
        <div x-show="approveModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
             x-on:open-approve-modal.window="approveModalOpen = true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                 <div x-show="approveModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="approveModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="approveModalOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Approve Objective</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Are you sure you want to approve this objective? This will unlock "In Progress" actions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form action="{{ route('objectives.approve', $objective) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Approve</button>
                        </form>
                        <button type="button" @click="approveModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Viewer Modal --}}
        <div x-show="docModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
             x-on:open-document-modal.window="docModalOpen = true; docUrl = $event.detail.url; docType = $event.detail.type;">
            <div class="flex items-center justify-center min-h-screen text-center sm:p-0">
                <div x-show="docModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="docModalOpen = false"></div>
                
                <button @click="docModalOpen = false" class="fixed top-4 right-4 text-white z-[60] focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div x-show="docModalOpen" class="inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-5xl my-8">
                     <div class="bg-gray-100 flex justify-between items-center px-4 py-2 border-b border-gray-200">
                         <h3 class="text-sm font-medium text-gray-700">Document Review</h3>
                         <a :href="docUrl" download class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                     </div>
                     <div class="p-4 bg-gray-50 text-center min-h-[50vh]">
                        <template x-if="docType === 'image'">
                            <img :src="docUrl" class="max-w-full max-h-[80vh] mx-auto shadow-sm">
                        </template>
                        <template x-if="docType === 'pdf'">
                            <iframe :src="docUrl" class="w-full h-[80vh] border border-gray-200"></iframe>
                        </template>
                     </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
