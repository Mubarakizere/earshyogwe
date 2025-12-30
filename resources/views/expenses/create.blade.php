<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Record Expense</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <div class="space-y-6">
                        @if($churches->count() > 1)
                            <div>
                                <label for="church_id" class="block text-sm font-medium text-gray-700 mb-2">Church <span class="text-red-500">*</span></label>
                                <select name="church_id" id="church_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Church</option>
                                    @foreach($churches as $church)
                                        <option value="{{ $church->id }}">{{ $church->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="church_id" value="{{ $churches->first()->id }}">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm font-medium text-blue-900">Recording for: <strong>{{ $churches->first()->name }}</strong></p>
                            </div>
                        @endif

                        <div>
                            <label for="expense_category_id" class="block text-sm font-medium text-gray-700 mb-2">Expense Category <span class="text-red-500">*</span></label>
                            <select name="expense_category_id" id="expense_category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (RWF) <span class="text-red-500">*</span></label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div>
                            <label for="receipt" class="block text-sm font-medium text-gray-700 mb-2">Receipt (Optional)</label>
                            <input type="file" name="receipt" id="receipt" accept=".jpg,.jpeg,.png,.pdf"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Upload receipt (JPG, PNG, or PDF, max 2MB)</p>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('expenses.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">Cancel</a>
                            <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md">Record Expense</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
