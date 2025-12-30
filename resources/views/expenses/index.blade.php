<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Expenses</h2>
            <a href="{{ route('expenses.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                + Record Expense
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Total Expenses</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totalAmount, 0) }} RWF</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm">Pending Approval</p>
                    <p class="text-3xl font-bold mt-2">{{ $pendingCount }}</p>
                </div>
            </div>

            <!-- Expenses Table -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                @if($expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->church->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $expense->expenseCategory->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ number_format($expense->amount, 0) }} RWF</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($expense->status === 'approved')
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                                            @elseif($expense->status === 'rejected')
                                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
                                            @else
                                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            @if($expense->status === 'pending' && (auth()->user()->hasRole('boss') || auth()->user()->hasRole('archid')))
                                                <form action="{{ route('expenses.approve', $expense) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $expenses->links() }}</div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500">No expenses recorded yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
