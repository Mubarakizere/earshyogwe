<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Activity Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Sticky Action Bar --}}
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm mb-6 -mt-12 pt-6 pb-4 px-6 -mx-6 sm:-mx-8 sm:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('activities.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to List
                        </a>
                        
                        <span class="text-gray-400">|</span>
                        
                        <div class="flex items-center gap-2">
                            @php
                                $statusColors = [
                                    'planned' => 'bg-gray-100 text-gray-800',
                                    'in_progress' => 'bg-blue-100 text-blue-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $statusLabel = ucfirst(str_replace('_', ' ', $activity->status));
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$activity->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabel }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $activity->department->name ?? 'No Department' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @can('edit activities')
                            <a href="{{ route('activities.edit', $activity) }}" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                        @endcan
                        
                        @can('delete activities')
                            <form action="{{ route('activities.destroy', $activity) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this activity? This action cannot be undone.');" 
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
            
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

                        {{-- Location REMOVED --}}

                    </div>

                    {{-- Custom Fields --}}
                    @if($activity->customValues && $activity->customValues->count() > 0)
                        <div class="mb-8">
                            <h4 class="text-lg font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Department-Specific Fields</h4>
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100 space-y-3">
                                @foreach($activity->customValues as $customValue)
                                    <div>
                                        <span class="block text-xs text-gray-400 uppercase tracking-wider">{{ $customValue->fieldDefinition->field_name }}</span>
                                        <span class="text-gray-900 font-medium">
                                            @if($customValue->fieldDefinition->field_type === 'checkbox')
                                                {{ $customValue->field_value ? 'Yes' : 'No' }}
                                            @elseif($customValue->fieldDefinition->field_type === 'date')
                                                {{ \Carbon\Carbon::parse($customValue->field_value)->format('M d, Y') }}
                                            @else
                                                {{ $customValue->field_value }}
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

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

                    <!-- Pro: Approval & Actions Section -->
                    @if($activity->approval_status === 'pending' && auth()->user()->can('approve activities'))
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This activity is waiting for approval.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <form action="{{ route('activities.reject', $activity) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this activity?');">
                                        @csrf
                                        <button type="submit" class="bg-white text-red-600 hover:bg-red-50 px-4 py-2 border border-red-200 rounded shadow-sm text-sm font-medium transition">
                                            Reject
                                        </button>
                                    </form>
                                    
                                    <button type="button" @click="$dispatch('open-approve-modal')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                                        Approve
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($activity->approval_status === 'rejected')
                         <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">This activity was rejected.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Pro: Completion Report -->
                    @if($activity->status === 'completed' && $activity->completion_summary)
                        <div class="bg-green-50 rounded-lg p-6 border border-green-200 mb-8">
                            <h4 class="text-lg font-bold text-green-900 mb-4 border-b border-green-200 pb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Completion Report
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <span class="block text-xs font-semibold text-green-700 uppercase">Summary</span>
                                    <p class="text-green-800 text-sm mt-1 whitespace-pre-line">{{ $activity->completion_summary }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <span class="block text-xs text-gray-500">Attendance</span>
                                        <span class="block text-xl font-bold text-gray-900">{{ number_format($activity->attendance_count) }}</span>
                                    </div>
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <span class="block text-xs text-gray-500">Salvations</span>
                                        <span class="block text-xl font-bold text-gray-900">{{ number_format($activity->salvation_count) }}</span>
                                    </div>
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <span class="block text-xs text-gray-500">Budget</span>
                                        <span class="block text-lg font-semibold text-gray-700">{{ number_format($activity->budget_estimate) }}</span>
                                    </div>
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <span class="block text-xs text-gray-500">Actual Spent</span>
                                        <span class="block text-lg font-bold {{ $activity->financial_spent > $activity->budget_estimate ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($activity->financial_spent) }}
                                        </span>
                                    </div>
                            </div>
                        </div>
                    @endif

                    {{-- Progress Timeline --}}
                    @if($activity->status !== 'cancelled')
                        @include('activities.partials.progress-timeline')
                    @endif

                    <!-- Bottom Action Buttons -->
                    <div class="flex items-center justify-between border-t border-gray-200 pt-6 mt-8">
                        <a href="{{ route('activities.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            ← Back to List
                        </a>
                        
                        @if($activity->status === 'in_progress' && $activity->approval_status === 'approved')
                             @can('edit activities')
                                <button type="button" @click="completionModalOpen = true" 
                                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md transition flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Mark as Complete
                                </button>
                             @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Completion Modal -->
    <div x-data="{ completionModalOpen: false }"
         x-show="completionModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div x-show="completionModalOpen" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="completionModalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="completionModalOpen" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('activities.complete', $activity) }}" method="POST" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Complete Activity Report</h3>
                        <p class="text-sm text-gray-500">Please provide the final numbers and a summary.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Completion Summary <span class="text-red-500">*</span></label>
                            <textarea name="completion_summary" rows="3" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Attendance</label>
                                <input type="number" name="attendance_count" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Salvations</label>
                                <input type="number" name="salvation_count" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-gray-700">Actual Financial Spent (RWF)</label>
                             <input type="number" name="financial_spent" min="0" step="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:col-start-2 sm:text-sm">
                            Submit Report
                        </button>
                        <button type="button" @click="completionModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Cloud be inside the main x-data, but let's add the modal code here -->
    
    <!-- Approval Modal -->
    <div x-data="{ approveModalOpen: false }"
         @keydown.escape.window="approveModalOpen = false"
         x-show="approveModalOpen"
         x-on:open-approve-modal.window="approveModalOpen = true" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div x-show="approveModalOpen" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="approveModalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="approveModalOpen" 
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Approve Activity
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to approve this activity? This will change its status to "In Progress" (or Planned) and allow it to proceed.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form action="{{ route('activities.approve', $activity) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Confirm Approval
                        </button>
                    </form>
                    <button type="button" @click="approveModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
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
