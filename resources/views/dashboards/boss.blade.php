<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <!-- Total Income -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-brand-50 text-brand-700 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Income</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalIncome) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-brand-50 text-brand-700 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Expenses</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalExpenses) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Net Balance -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-brand-50 text-brand-700 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Net Balance</p>
                            <p class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-gray-900' : 'text-brand-600' }}">{{ number_format($netBalance) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-brand-50 text-brand-700 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Attendance</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAttendance) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Population -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-brand-50 text-brand-700 mr-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Census</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPopulation) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Financial Trends</h3>
                    <div class="relative h-80">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Top Performing Churches -->
                <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6">Top Contributing Churches</h3>
                    <div class="space-y-4">
                        @foreach($topChurches as $church)
                            <div class="flex items-center justify-between border-b border-gray-50 pb-3 last:border-0 hover:bg-gray-50 rounded-lg px-2 transition">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $church->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $church->location }}</p>
                                </div>
                                <span class="font-bold text-brand-700">{{ number_format($church->givings_sum_amount) }} RWF</span>
                            </div>
                        @endforeach
                        <div class="mt-6 text-center">
                            <a href="{{ route('churches.index') }}" class="inline-flex items-center text-sm text-brand-600 hover:text-brand-800 font-semibold transition">
                                View All Churches 
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Givings -->
                <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Recent Givings</h3>
                        <a href="{{ route('givings.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($recentGivings as $giving)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium truncate max-w-xs">{{ $giving->church->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $giving->givingType->name }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-brand-700">{{ number_format($giving->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-sm text-gray-500 text-center">No recent givings found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-md rounded-xl border border-gray-100">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Recent Expenses</h3>
                        <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($recentExpenses as $expense)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium truncate max-w-xs">{{ $expense->church->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $expense->expenseCategory->name }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-red-600">{{ number_format($expense->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-sm text-gray-500 text-center">No recent expenses found.</td></tr>
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
                        backgroundColor: '#c81e1e', // Brand 700
                        borderRadius: 4,
                        hoverBackgroundColor: '#9b1c1c'
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyStats['expenses']),
                        backgroundColor: '#e5e7eb', // Gray 200
                        borderRadius: 4,
                        hoverBackgroundColor: '#d1d5db'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            font: {
                                family: 'Figtree'
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: {
                                family: 'Figtree'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Figtree'
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
