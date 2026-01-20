<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Offering Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('givings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-200">
                    Back to List
                </a>
                @can('enter givings')
                <a href="{{ route('givings.create') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Record New
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
            editOpen: false, 
            editAction: '', 
            editAmount: '', 
            editType: '',
            editTypeId: '',
            
            deleteOpen: false,
            deleteAction: '',
            
            deleteAllOpen: false,

            initEdit(action, amount, typeName, typeId) {
                this.editAction = action;
                this.editAmount = amount;
                this.editType = typeName;
                this.editTypeId = typeId;
                this.editOpen = true;
            }
         }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Grid -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Parish</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ $church->name }}</h3>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Date</p>
                        <h3 class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($date)->format('l, M d, Y') }}</h3>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Total Collection</p>
                        <div class="flex items-baseline justify-end">
                            <h3 class="text-3xl font-bold text-blue-600">{{ number_format($sessionTotal, 0) }}</h3>
                            <span class="ml-1 text-gray-500 font-medium">RWF</span>
                        </div>
                    </div>
                </div>

                <!-- Status & Global Actions -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 uppercase font-medium">Status</span>
                            @if($isSent)
                                <span class="text-green-600 font-bold flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sent to Diocese
                                </span>
                            @else
                                <span class="text-gray-500 font-medium flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Pending Transfer
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-col ml-6">
                            <span class="text-xs text-gray-500 uppercase font-medium">Diocese Share</span>
                            <span class="text-indigo-600 font-bold mt-1">{{ number_format($sessionDioceseTotal, 0) }} RWF</span>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        @if($records->isNotEmpty())
                            @can('enter givings')
                                <button @click="deleteAllOpen = true" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete All Offerings
                                </button>
                            @endcan
                        @endif
                        
                        @if(!$isSent && $records->isNotEmpty())
                            @can('mark diocese transfer')
                                <form action="{{ route('givings.markAsSent', $records->first()) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Confirm sending to diocese?')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                                        Mark Sent to Diocese
                                    </button>
                                </form>
                            @endcan
                        @elseif($isSent && $records->first()->receipt_status === 'pending')
                            @can('verify diocese receipt')
                                <form action="{{ route('givings.verifyReceipt', $records->first()) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                                        Verify Receipt
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>

            <!-- Matrix Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Offering Type</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Recorded</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allTypes as $type)
                            @php
                                $record = $typeMap[$type->id] ?? null;
                                $amount = $record ? $record->amount : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors {{ $record ? '' : 'bg-gray-50/50' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    {{ $type->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if($record)
                                        <span class="text-base font-bold text-gray-900">{{ number_format($amount, 0) }}</span>
                                    @else
                                        <span class="text-sm text-gray-400 font-medium">0</span>
                                    @endif
                                    <span class="text-xs text-gray-500 ml-1">RWF</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($record)
                                        @can('enter givings')
                                            <!-- Edit Button -->
                                            <button @click="initEdit('{{ route('givings.update', $record) }}', '{{ $amount }}', '{{ $type->name }}', '{{ $type->id }}')" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edit
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button @click="deleteOpen = true; deleteAction = '{{ route('givings.destroy', $record) }}'" 
                                                class="text-red-500 hover:text-red-700">
                                                Delete
                                            </button>
                                        @endcan
                                    @else
                                         <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Edit Modal -->
        <div x-show="editOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editOpen = false"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full z-50 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Edit <span x-text="editType"></span></h3>
                    <form :action="editAction" method="POST">
                        @csrf @method('PUT')
                        
                        <!-- Hidden Inputs for Validation Context -->
                        <input type="hidden" name="church_id" value="{{ $church->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="giving_type_id" x-model="editTypeId">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (RWF)</label>
                            <input type="number" step="0.01" name="amount" x-model="editAmount" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="mb-6">
                             <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                             <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" @click="editOpen = false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation -->
         <div x-show="deleteOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                 <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteOpen = false"></div>
                 <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full z-50 p-6">
                     <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                     </div>
                     <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete Record</h3>
                     <p class="text-sm text-gray-500 text-center mb-6">Are you sure you want to delete this offering record? This cannot be undone.</p>
                     <form :action="deleteAction" method="POST" class="flex justify-center space-x-3">
                         @csrf @method('DELETE')
                         <button type="button" @click="deleteOpen = false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">Cancel</button>
                         <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">Delete Record</button>
                     </form>
                 </div>
            </div>
         </div>
         
         <!-- Delete All Confirmation Modal -->
         <div x-show="deleteAllOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="deleteAllOpen = false"></div>
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full z-50 p-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Delete All Offerings</h3>
                    <p class="text-sm text-gray-500 text-center mb-2">Are you sure you want to delete <strong>ALL {{ $records->count() }} offering records</strong> for this date and parish?</p>
                    <p class="text-xs text-red-600 text-center mb-6 font-medium">This action cannot be undone!</p>
                    <form action="{{ route('givings.destroyBulk', ['date' => $date, 'church_id' => $church->id]) }}" method="POST" class="flex justify-center space-x-3">
                        @csrf @method('DELETE')
                        <button type="button" @click="deleteAllOpen = false" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium">Delete All {{ $records->count() }} Records</button>
                    </form>
                </div>
            </div>
         </div>
    </div>
</x-app-layout>
