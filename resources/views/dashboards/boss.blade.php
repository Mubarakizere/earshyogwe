<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Diocese Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Income (YTD)</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalIncome) }} RWF</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Expenses (YTD)</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalExpenses) }} RWF</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Net Balance</p>
                            <p class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-blue-800' : 'text-red-600' }}">{{ number_format($netBalance) }} RWF</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Attendance</p>
                            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalAttendance) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Overview (Current Year)</h3>
                    <div class="relative h-80">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Top Performing Churches -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Churches (Income)</h3>
                    <div class="space-y-4">
                        @foreach($topChurches as $church)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $church->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $church->location }}</p>
                                </div>
                                <span class="font-bold text-green-600">{{ number_format($church->givings_sum_amount) }} RWF</span>
                            </div>
                        @endforeach
                        <div class="mt-4 text-center">
                            <a href="{{ route('churches.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All Churches &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Givings -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Givings</h3>
                        <a href="{{ route('givings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentGivings as $giving)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs">{{ $giving->church->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $giving->givingType->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-green-600">{{ number_format($giving->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No recent givings</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Expenses</h3>
                        <a href="{{ route('expenses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentExpenses as $expense)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs">{{ $expense->church->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $expense->expenseCategory->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-red-600">{{ number_format($expense->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-3 text-sm text-gray-500 text-center">No recent expenses</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        const financialChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($monthlyStats['labels']),
                datasets: [
                    {
                        label: 'Income',
                        data: @json($monthlyStats['income']),
                        backgroundColor: 'rgba(34, 197, 94, 0.6)', // Green-500
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyStats['expenses']),
                        backgroundColor: 'rgba(239, 68, 68, 0.6)', // Red-500
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>
