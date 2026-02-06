<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Expenses</h2>
            @can('enter expenses')
            <a href="{{ route('expenses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Record Expense
            </a>
            @endcan

            <div x-data="{ open: false }" class="relative ml-2">
                <button @click="open = !open" type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <a href="{{ route('expenses.export', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export as CSV (Excel)
                        </a>
                        <a href="{{ route('expenses.exportPdf', request()->query()) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Export as PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-emerald-100">Total Expenses</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalAmount, 0) }} <span class="text-lg font-normal">RWF</span></p>
                    </div>
                    <svg class="absolute right-0 bottom-0 h-24 w-24 -mr-4 -mb-4 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-lg shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-amber-100">Pending Approval</p>
                        <p class="text-3xl font-bold mt-2">{{ $pendingCount }}</p>
                    </div>
                    <svg class="absolute right-0 bottom-0 h-24 w-24 -mr-4 -mb-4 text-white opacity-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-lg shadow-sm p-5 mb-8 border border-gray-100">
                <form action="{{ route('expenses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <!-- Parish (Visible if multiple available) -->
                    @if($churches->count() > 1 || auth()->user()->can('view all churches'))
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Parish</label>
                        <select name="church_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Parishes</option>
                            @foreach($churches as $church)
                                <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Category</label>
                        <select name="expense_category_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('expense_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                        <select name="status" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex flex-col justify-end">
                        <label class="block text-xs font-semibold text-transparent uppercase tracking-wider mb-1">Action</label>
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 px-4 rounded-md shadow transition flex-1 flex justify-center items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filter
                            </button>
                            <a href="{{ route('expenses.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium py-2 px-4 rounded-md shadow-sm transition">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Expenses List -->
            @if($expenses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($expenses as $expense)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300 flex flex-col">
                            <!-- Card Header -->
                            <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500 font-medium">{{ $expense->date->format('M d, Y') }}</p>
                                    <h3 class="font-bold text-gray-900 mt-1">{{ $expense->expenseCategory->name }}</h3>
                                </div>
                                <div>
                                    @if($expense->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($expense->status === 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 flex-1">
                                <p class="text-2xl font-bold text-gray-900 mb-2">{{ number_format($expense->amount, 0) }} <span class="text-sm font-normal text-gray-500">RWF</span></p>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $expense->description ?: 'No description provided.' }}</p>
                                
                                <div class="flex items-center justify-between text-xs text-gray-500 mt-auto pt-4 border-t border-gray-100">
                                    <span class="truncate pr-2">By: {{ $expense->enteredBy->name ?? 'Unknown' }}</span>
                                    <span>{{ $expense->church->name ?? 'N/A' }}</span>
                                </div>
                                
                                @if($expense->receipt_path)
                                    @php
                                        $extension = pathinfo($expense->receipt_path, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $url = Storage::url($expense->receipt_path);
                                    @endphp
                                    <button type="button" 
                                        @click="$dispatch('open-receipt-modal', { 
                                            url: '{{ $url }}', 
                                            type: '{{ $isPdf ? 'pdf' : 'image' }}'
                                        })"
                                        class="mt-3 inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium focus:outline-none">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        View Receipt
                                    </button>
                                @endif
                            </div>

                            <!-- Action Footer -->
                            <div class="bg-gray-50 p-3 flex justify-between items-center border-t border-gray-100">
                                <div class="flex space-x-2">
                                    @if(auth()->user()->can('approve expenses') || ($expense->entered_by === auth()->id() && $expense->status === 'pending'))
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-gray-600 hover:text-blue-600 p-1" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button type="button" 
                                                @click="$dispatch('open-delete-modal', { url: '{{ route('expenses.destroy', $expense) }}' })"
                                                class="text-gray-600 hover:text-red-600 p-1" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    @endif
                                </div>

                                @if($expense->status === 'pending' && auth()->user()->can('approve expenses'))
                                    <div class="flex space-x-2">
                                        <form action="{{ route('expenses.reject', $expense) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-white border border-red-200 text-red-600 hover:bg-red-50 px-2 py-1 rounded shadow-sm font-medium transition">
                                                Reject
                                            </button>
                                        </form>
                                        <form action="{{ route('expenses.approve', $expense) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-white border border-green-200 text-green-600 hover:bg-green-50 px-2 py-1 rounded shadow-sm font-medium transition">
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $expenses->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 text-center py-16">
                    <div class="bg-gray-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No expenses found</h3>
                    <p class="text-gray-500 mt-1 mb-6">Start by recording a new expense or adjust your filters.</p>
                    @can('enter expenses')
                    <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Record Expense
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
    <!-- Receipt Viewer Modal -->
    <div x-data="{ open: false, url: '', type: '' }" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-on:open-receipt-modal.window="
            open = true; 
            url = $event.detail.url;
            type = $event.detail.type;
         ">
        <div class="flex items-center justify-center min-h-screen text-center sm:p-0">
            <div x-show="open" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-900 opacity-95"></div>
            </div>
            
            <!-- Close button -->
            <button @click="open = false" class="fixed top-4 right-4 text-white hover:text-gray-300 z-[60] focus:outline-none p-2 bg-black bg-opacity-20 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Modal Panel -->
            <div x-show="open" 
                 class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full h-full sm:h-auto sm:w-full sm:max-w-5xl max-h-[95vh] flex flex-col">
                <div class="bg-gray-100 px-4 py-3 border-b flex justify-between items-center shrink-0">
                    <h3 class="text-lg font-medium text-gray-900">Receipt Viewer</h3>
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
    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false, url: '' }" 
         @keydown.escape.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-on:open-delete-modal.window="
            open = true; 
            url = $event.detail.url;
         ">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="open = false">
                <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Delete Expense</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete this expense? This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form :action="url" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                    </form>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
