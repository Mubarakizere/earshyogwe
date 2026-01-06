<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Givings') }}
            </h2>
            <a href="{{ route('givings.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                <svg class="inline-block w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record Giving
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <!-- Filter Bar -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <form action="{{ route('givings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Giving Type -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Giving Type</label>
                        <select name="giving_type_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Types</option>
                            @foreach($givingTypes as $type)
                                <option value="{{ $type->id }}" {{ request('giving_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Church Filter (if user has access to multiple) -->
                    @if(auth()->user()->hasRole('boss') || auth()->user()->hasRole('archid'))
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                        <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Churches</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                        <!-- Spacer for alignment if not admin -->
                        <div class="hidden md:block"></div> 
                    @endif

                    <!-- Filter Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md shadow-sm">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ route('givings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-4 rounded-md shadow-sm">Reset</a>
                    </div>
                </form>

                <!-- Export & Print Options -->
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('givings.export', request()->query()) }}" class="flex items-center text-green-700 bg-green-100 hover:bg-green-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export CSV
                    </a>
                    <button onclick="window.print()" class="flex items-center text-gray-700 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-md text-sm font-medium transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print View
                    </button>
                </div>
            </div>

            <style>
                @media print {
                    /* Hide everything we don't need */
                    nav, header, footer, .filter-section, button, a[href*="export"] {
                        display: none !important;
                    }
                    /* Ensure table and summary cards are visible and look good */
                    body { background: white; }
                    .bg-white, .shadow-xl, .shadow-lg, .rounded-lg { 
                        box-shadow: none !important; 
                        border-radius: 0 !important; 
                    }
                    /* Ensure we print the whole table */
                    .overflow-x-auto { overflow: visible !important; }
                    
                    /* Custom Print Header */
                    .py-12::before {
                        content: "Givings Report - " attr(data-date);
                        display: block;
                        font-size: 20pt;
                        font-weight: bold;
                        margin-bottom: 20px;
                        text-align: center;
                    }
                }
            </style>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Givings</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($totalAmount, 0) }} RWF</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Sent to Diocese</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($sentToDiocese, 0) }} RWF</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Givings Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    @if($givings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Church</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diocese</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($givings as $giving)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $giving->date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $giving->church->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $giving->givingType->name }}</div>
                                                @if($giving->givingSubType)
                                                    <div class="text-xs text-gray-500">{{ $giving->givingSubType->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ number_format($giving->amount, 0) }} RWF
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($giving->diocese_received)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        Received
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $giving->diocese_received_date ? $giving->diocese_received_date->format('M d') : '' }}
                                                    </div>
                                                @elseif($giving->sent_to_diocese)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Sent
                                                    </span>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $giving->diocese_sent_date ? $giving->diocese_sent_date->format('M d') : '' }}
                                                    </div>

                                                    @can('verify diocese receipt')
                                                        <form action="{{ route('givings.verifyReceipt', $giving) }}" method="POST" class="mt-2 inline-block">
                                                            @csrf
                                                            <button type="submit" class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded shadow-sm">
                                                                Confirm Receipt
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Pending
                                                    </span>

                                                    @can('mark diocese transfer')
                                                        <form action="{{ route('givings.markAsSent', $giving) }}" method="POST" class="mt-2 inline-block">
                                                            @csrf
                                                            <button type="submit" class="text-xs bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded shadow-sm">
                                                                Mark Sent
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                <button type="button" 
                                                    @click="$dispatch('open-edit-modal', { 
                                                        action: '{{ route('givings.update', $giving) }}',
                                                        church_id: '{{ $giving->church_id }}',
                                                        giving_type_id: '{{ $giving->giving_type_id }}',
                                                        giving_sub_type_id: '{{ $giving->giving_sub_type_id }}',
                                                        amount: '{{ $giving->amount }}',
                                                        date: '{{ $giving->date->format('Y-m-d') }}',
                                                        notes: '{{ addslashes($giving->notes) }}'
                                                    })"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    Edit
                                                </button>
                                                <button type="button" 
                                                    @click="$dispatch('open-delete-modal', { action: '{{ route('givings.destroy', $giving) }}' })" 
                                                    class="text-red-600 hover:text-red-900">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $givings->links() }}
                        </div>
                    @else
                        <!-- (Empty State remains same) -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No givings recorded</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by recording your first giving.</p>
                            <div class="mt-6">
                                <a href="{{ route('givings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Record Giving
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false, action: '' }" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-on:open-delete-modal.window="open = true; action = $event.detail.action">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Giving Record
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this giving record? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sm:items-center">
                    <form :action="action" method="POST" class="w-full sm:w-auto sm:ml-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-data="{ 
            open: false, 
            action: '', 
            church_id: '',
            giving_type_id: '',
            giving_sub_type_id: '',
            amount: '',
            date: '',
            notes: '',
            subTypes: [],
            hasSubTypes: false,
            
            initEdit(data) {
                this.action = data.action;
                this.church_id = data.church_id;
                this.giving_type_id = data.giving_type_id;
                this.amount = data.amount;
                this.date = data.date;
                this.notes = data.notes;
                this.giving_sub_type_id = data.giving_sub_type_id;
                
                // Trigger sub-type update logic manually after setting ID
                this.$nextTick(() => {
                    this.updateSubTypes(true); 
                });
                
                this.open = true;
            },
            
            updateSubTypes(retainValue = false) {
                const select = document.getElementById('edit_giving_type_id'); // We'll id the select 
                if(!select) return;
                const option = select.querySelector(`option[value='${this.giving_type_id}']`);
                if(!option) return;

                this.hasSubTypes = option.dataset.hasSubtypes === '1';
                this.subTypes = JSON.parse(option.dataset.subtypes || '[]');
                
                if (!retainValue) {
                    this.giving_sub_type_id = '';
                }
            }
         }" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-on:open-edit-modal.window="initEdit($event.detail)">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">
                            Edit Giving Record
                        </h3>
                    </div>
                    
                    <form :action="action" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <!-- Church Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Church</label>
                                @if(count($churches) > 1)
                                    <select name="church_id" x-model="church_id" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($churches as $church)
                                            <option value="{{ $church->id }}">{{ $church->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="hidden" name="church_id" x-model="church_id">
                                    <div class="p-2 bg-gray-50 rounded border border-gray-200 text-sm text-gray-700">
                                        {{ $churches->first()->name ?? 'N/A' }}
                                    </div>
                                @endif
                            </div>

                            <!-- Giving Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Giving Type</label>
                                <select name="giving_type_id" id="edit_giving_type_id" x-model="giving_type_id" @change="updateSubTypes()" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($givingTypes as $type)
                                        <option value="{{ $type->id }}" 
                                            data-has-subtypes="{{ $type->has_sub_types }}" 
                                            data-subtypes="{{ $type->subTypes->toJson() }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sub Type -->
                            <div x-show="hasSubTypes" style="display: none;">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Type</label>
                                <select name="giving_sub_type_id" x-model="giving_sub_type_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Sub-Type</option>
                                    <template x-for="subType in subTypes" :key="subType.id">
                                        <option :value="subType.id" x-text="subType.name"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                    <input type="number" step="0.01" name="amount" x-model="amount" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" name="date" x-model="date" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea name="notes" x-model="notes" rows="3" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>

                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-600 text-base font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 sm:col-start-2 sm:text-sm">
                                    Save Changes
                                </button>
                                <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
