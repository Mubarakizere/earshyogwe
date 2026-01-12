<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Offerings Management') }}
            </h2>
            <a href="{{ route('givings.create') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record New Offering
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
            deleteOpen: false, 
            deleteAction: '',
            markSentOpen: false,
            markSentAction: '',
            verifyOpen: false,
            verifyAction: '',
            
            // Edit Modal Data
            editOpen: false, 
            editAction: '', 
            editChurchId: '',
            editGivingTypeId: '',
            editGivingSubTypeId: '',
            editAmount: '',
            editDate: '',
            editNotes: '',
            subTypes: [],
            hasSubTypes: false,
            
            initEdit(data) {
                this.editAction = data.action;
                this.editChurchId = data.church_id;
                this.editGivingTypeId = data.giving_type_id;
                this.editAmount = data.amount;
                this.editDate = data.date;
                this.editNotes = data.notes;
                this.editGivingSubTypeId = data.giving_sub_type_id;
                
                // Trigger sub-type update logic properly
                this.$nextTick(() => {
                    this.updateSubTypes(true); 
                });
                
                this.editOpen = true;
            },
            
            updateSubTypes(retainValue = false) {
                const select = document.getElementById('edit_giving_type_id');
                if(!select) return;
                
                // Find selected option manually since x-model might not be synced yet if called programmatically
                const option = Array.from(select.options).find(o => o.value == this.editGivingTypeId);
                
                if(!option) {
                    this.hasSubTypes = false;
                    this.subTypes = [];
                    return;
                }

                this.hasSubTypes = option.dataset.hasSubtypes === '1';
                this.subTypes = JSON.parse(option.dataset.subtypes || '[]');
                
                if (!retainValue) {
                    this.editGivingSubTypeId = '';
                }
            }
         }"
         @keydown.escape.window="deleteOpen = false; markSentOpen = false; verifyOpen = false; editOpen = false">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Received -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider">Total Collection</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-3xl font-bold">{{ number_format($totalAmount, 0) }}</h3>
                            <span class="ml-1 text-lg opacity-80">RWF</span>
                        </div>
                        <p class="text-sm text-blue-200 mt-2">
                            Filtered Period Total
                        </p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Diocese Transfer -->
                <div class="bg-gradient-to-r from-fuchsia-600 to-pink-700 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-fuchsia-100 text-sm font-medium uppercase tracking-wider">Diocese Share</p>
                        <div class="flex items-baseline mt-2">
                            <h3 class="text-3xl font-bold">{{ number_format($sentToDiocese, 0) }}</h3>
                            <span class="ml-1 text-lg opacity-80">RWF</span>
                        </div>
                        <p class="text-sm text-fuchsia-200 mt-2">
                            Marked as Sent
                        </p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-2 translate-y-2">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pro Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 mb-6">
                <form action="{{ route('givings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <!-- Date Range (Col 1-4) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Giving Type (Col 5-7) -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Giving Type</label>
                        <select name="giving_type_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Types</option>
                            @foreach($givingTypes as $type)
                                <option value="{{ $type->id }}" {{ request('giving_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Church Filter (Role Based) (Col 8-10) -->
                    <div class="md:col-span-3">
                        @if($churches->count() > 1)
                            <label class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                            <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Churches</option>
                                @foreach($churches as $church)
                                    <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                @endforeach
                            </select>
                        @elseif($churches->count() == 1)
                            <!-- Hidden input for single church users if needed, but usually handled by controller defaults -->
                            <label class="block text-xs font-medium text-gray-500 mb-1">Church</label>
                            <input type="text" disabled value="{{ $churches->first()->name }}" class="w-full text-sm bg-gray-50 rounded-md border-gray-200 text-gray-500 cursor-not-allowed">
                        @endif
                    </div>

                    <!-- Actions (Col 11-12) -->
                    <div class="md:col-span-2 flex space-x-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-3 rounded-md shadow-sm flex items-center justify-center transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Filter
                        </button>
                        <a href="{{ route('givings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium py-2 px-3 rounded-md shadow-sm border border-gray-200 flex items-center justify-center transition" title="Reset Filters">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    </div>
                </form>
                
                <div class="flex justify-end mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('givings.export', request()->query()) }}" class="text-sm text-green-700 hover:text-green-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Download CSV Report
                    </a>
                </div>
            </div>

            <!-- Content Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <div class="p-0">
                    @if($givings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Church</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($givings as $giving)
                                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $giving->date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs ring-2 ring-white">
                                                        {{ $giving->church ? substr($giving->church->name, 0, 2) : '??' }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">{{ $giving->church ? $giving->church->name : 'Church Not Found' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $giving->givingType->name }}</div>
                                                @if($giving->givingSubType)
                                                    <div class="text-xs text-gray-500">{{ $giving->givingSubType->name }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-gray-900">{{ number_format($giving->amount, 0) }}</span>
                                                <span class="text-xs text-gray-500 ml-1">RWF</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($giving->receipt_status === 'verified')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                        Verified
                                                    </span>
                                                @elseif($giving->receipt_status === 'rejected')
                                                     <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                        Rejected
                                                    </span>
                                                @elseif($giving->sent_to_diocese)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                        Sent / Pending
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Not Sent
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                
                                                <!-- Verify Action (Boss/Finance) -->
                                                @if($giving->sent_to_diocese && $giving->receipt_status === 'pending')
                                                    @can('verify diocese receipt')
                                                        <button type="button" 
                                                            @click="verifyOpen = true; verifyAction = '{{ route('givings.verifyReceipt', $giving) }}'"
                                                            class="text-blue-600 hover:text-blue-900 text-xs uppercase font-bold tracking-wider">
                                                            Verify
                                                        </button>
                                                        <span class="text-gray-300">|</span>
                                                    @endcan
                                                @endif

                                                <!-- Mark Sent Action (Pastor) -->
                                                @if(!$giving->sent_to_diocese)
                                                    @can('mark diocese transfer')
                                                        <button type="button" 
                                                            @click="markSentOpen = true; markSentAction = '{{ route('givings.markAsSent', $giving) }}'"
                                                            class="text-green-600 hover:text-green-900 text-xs uppercase font-bold tracking-wider">
                                                            Mark Sent
                                                        </button>
                                                        <span class="text-gray-300">|</span>
                                                    @endcan
                                                @endif

                                                <!-- Edit -->
                                                @can('enter givings')
                                                <button type="button" 
                                                    @click="initEdit({ 
                                                        action: '{{ route('givings.update', $giving) }}',
                                                        church_id: '{{ $giving->church_id }}',
                                                        giving_type_id: '{{ $giving->giving_type_id }}',
                                                        giving_sub_type_id: '{{ $giving->giving_sub_type_id }}',
                                                        amount: '{{ $giving->amount }}',
                                                        date: '{{ $giving->date->format('Y-m-d') }}',
                                                        notes: '{{ addslashes($giving->notes) }}'
                                                    })"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                                @endcan
                                                
                                                <!-- Delete -->
                                                @can('enter givings')
                                                <button type="button" 
                                                    @click="deleteOpen = true; deleteAction = '{{ route('givings.destroy', $giving) }}'" 
                                                    class="text-red-400 hover:text-red-600">
                                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                            {{ $givings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No offerings found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or record a new offering.</p>
                            <div class="mt-6">
                                <a href="{{ route('givings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Record Offering
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ================= MODALS ================= -->

        <!-- 1. Delete Modal -->
        <div x-show="deleteOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="deleteOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="deleteOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Record</h3>
                                <div class="mt-2"><p class="text-sm text-gray-500">Are you sure? This cannot be undone.</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="deleteAction" method="POST" class="w-full sm:w-auto sm:ml-3">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">Delete</button>
                        </form>
                        <button type="button" @click="deleteOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Mark Sent Modal -->
        <div x-show="markSentOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="markSentOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="markSentOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="markSentOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Transfer</h3>
                                <div class="mt-2"><p class="text-sm text-gray-500">Mark this as sent to Diocese? This notifies the finance team.</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="markSentAction" method="POST" class="w-full sm:w-auto sm:ml-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:w-auto sm:text-sm">Confirm</button>
                        </form>
                        <button type="button" @click="markSentOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Verify Receipt Modal -->
        <div x-show="verifyOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="verifyOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="verifyOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="verifyOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Verify Receipt</h3>
                                <div class="mt-2"><p class="text-sm text-gray-500">Confirm you have received these funds?</p></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <form :action="verifyAction" method="POST" class="w-full sm:w-auto sm:ml-3">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:w-auto sm:text-sm">Verify</button>
                        </form>
                        <button type="button" @click="verifyOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Edit Modal -->
        <div x-show="editOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editOpen" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="editOpen" class="inline-block align-bottom bg-white rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-4 pb-4 border-b border-gray-100">
                            <h3 class="text-xl font-bold text-gray-900">Edit Offering Record</h3>
                        </div>
                        <form :action="editAction" method="POST">
                            @csrf @method('PUT')
                            <div class="space-y-4">
                                <!-- Form Fields -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Church</label>
                                    @if(count($churches) > 1)
                                        <select name="church_id" x-model="editChurchId" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            @foreach($churches as $church)
                                                <option value="{{ $church->id }}">{{ $church->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="hidden" name="church_id" x-model="editChurchId">
                                        <div class="p-2 bg-gray-50 rounded border border-gray-200 text-sm text-gray-700">{{ $churches->first()->name ?? 'N/A' }}</div>
                                    @endif
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Giving Type</label>
                                    <select name="giving_type_id" id="edit_giving_type_id" x-model="editGivingTypeId" @change="updateSubTypes()" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($givingTypes as $type)
                                            <option value="{{ $type->id }}" data-has-subtypes="{{ $type->has_sub_types }}" data-subtypes="{{ $type->subTypes->toJson() }}">
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div x-show="hasSubTypes" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub-Type</label>
                                    <select name="giving_sub_type_id" x-model="editGivingSubTypeId" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Sub-Type</option>
                                        <template x-for="subType in subTypes" :key="subType.id">
                                            <option :value="subType.id" x-text="subType.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                                        <input type="number" step="0.01" name="amount" x-model="editAmount" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                        <input type="date" name="date" x-model="editDate" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea name="notes" x-model="editNotes" rows="3" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                </div>

                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:col-start-2 sm:text-sm">Save Changes</button>
                                    <button type="button" @click="editOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:col-start-1 sm:text-sm">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
