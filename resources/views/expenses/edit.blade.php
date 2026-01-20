<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Edit Expense</h2>
    </x-slot>

    <div class="py-12" x-data="{ docModalOpen: false, docUrl: '', docType: '' }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        @if($churches->count() > 1)
                            <div>
                                <label for="church_id" class="block text-sm font-medium text-gray-700 mb-2">Parish <span class="text-red-500">*</span></label>
                                <select name="church_id" id="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Parish</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}" {{ old('church_id', $expense->church_id) == $church->id ? 'selected' : '' }}>{{ $church->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $expense->church_id }}">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-blue-900">Editing for: <strong>{{ $expense->church->name }}</strong></p>
                            </div>
                        @endif

                        <div>
                            <label for="expense_category_id" class="block text-sm font-medium text-gray-700 mb-2">Expense Category <span class="text-red-500">*</span></label>
                            <select name="expense_category_id" id="expense_category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('expense_category_id', $expense->expense_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (RWF) <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $expense->amount) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $expense->description) }}</textarea>
                        </div>

                        <div>
                            <label for="receipt" class="block text-sm font-medium text-gray-700 mb-2">Receipt (Optional)</label>
                            @if($expense->receipt_path)
                                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 mb-2">Current Receipt:</p>
                                    @php
                                        $extension = pathinfo($expense->receipt_path, PATHINFO_EXTENSION);
                                        $isPdf = strtolower($extension) === 'pdf';
                                        $url = Storage::url($expense->receipt_path);
                                    @endphp
                                    <button 
                                        type="button"
                                        @click="$dispatch('open-document-modal', { url: '{{ $url }}', type: '{{ $isPdf ? 'pdf' : 'image' }}' })"
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View Current Receipt
                                    </button>
                                </div>
                            @endif
                            <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Upload new receipt to replace existing one (JPG, PNG, or PDF, max 2MB)</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('expenses.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md">Update Expense</button>
                        </div>
                    </div>
                </form>
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
                         <h3 class="text-sm font-medium text-gray-700">Receipt Viewer</h3>
                         <a :href="docUrl" download class="text-blue-600 hover:text-blue-900 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download
                         </a>
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
