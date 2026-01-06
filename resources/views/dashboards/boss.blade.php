<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('Dashboard Overview') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">Welcome back, here's what's happening in your diocese today.</p>
                </div>
                <div class="mt-4 md:mt-0">
                     <span class="inline-flex rounded-md shadow-sm">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                             <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Generate Report
                        </button>
                    </span>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-10">
                <!-- Total Income -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 transition duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Income</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalIncome) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-50 text-green-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 transition duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Expenses</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalExpenses) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-50 text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Net Balance -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 transition duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Net Balance</p>
                            <p class="mt-2 text-3xl font-bold {{ $netBalance >= 0 ? 'text-gray-900' : 'text-red-600' }}">{{ number_format($netBalance) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Attendance -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 transition duration-300 hover:shadow-md transform hover:-translate-y-1">
                    <div class="flex items-start justify-between">
                         <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Attendance</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalAttendance) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Population -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 transition duration-300 hover:shadow-md transform hover:-translate-y-1">
                     <div class="flex items-start justify-between">
                         <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Census</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalPopulation) }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-orange-50 text-orange-600">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Financial Trends</h3>
                        <div class="text-sm text-gray-500">Last 12 Months</div>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Top Performing Churches -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                         <h3 class="text-lg font-bold text-gray-800">Top Churches</h3>
                         <span class="text-xs font-semibold bg-gray-100 text-gray-600 py-1 px-2 rounded">By Giving</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($topChurches as $church)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition duration-150">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 font-bold text-sm mr-3">
                                        {{ substr($church->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $church->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $church->location }}</p>
                                    </div>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">{{ number_format($church->givings_sum_amount) }}</span>
                            </div>
                        @endforeach
                        <div class="pt-4 mt-2 border-t border-gray-100 text-center">
                            <a href="{{ route('churches.index') }}" class="inline-flex items-center text-sm text-brand-600 hover:text-brand-800 font-medium transition">
                                View Full Leaderboard
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Givings -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Recent Givings</h3>
                        <a href="{{ route('givings.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($recentGivings as $giving)
                                    <tr class="hover:bg-gray-50 transition cursor-pointer group" 
                                        onclick="openQuickView({
                                            type: 'giving',
                                            title: 'Giving Details',
                                            church: '{{ addslashes($giving->church->name) }}',
                                            category: '{{ addslashes($giving->givingType->name) }}',
                                            amount: '{{ number_format($giving->amount) }} RWF',
                                            date: '{{ $giving->created_at->format('M d, Y') }}',
                                            user: '{{ $giving->user ? addslashes($giving->user->name) : 'System' }}'
                                        })">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium truncate max-w-xs group-hover:text-brand-600">{{ $giving->church->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $giving->givingType->name }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-gray-900">{{ number_format($giving->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-sm text-gray-500 text-center italic">No recent givings records.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Recent Expenses</h3>
                        <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($recentExpenses as $expense)
                                    <tr class="hover:bg-gray-50 transition cursor-pointer group"
                                        onclick="openQuickView({
                                            type: 'expense',
                                            title: 'Expense Details',
                                            church: '{{ addslashes($expense->church->name) }}',
                                            category: '{{ addslashes($expense->expenseCategory->name) }}',
                                            amount: '{{ number_format($expense->amount) }} RWF',
                                            description: '{{ addslashes($expense->description ?? '') }}',
                                            date: '{{ $expense->created_at->format('M d, Y') }}'
                                        })">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium truncate max-w-xs group-hover:text-brand-600">{{ $expense->church->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $expense->expenseCategory->name }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-red-600">{{ number_format($expense->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-8 text-sm text-gray-500 text-center italic">No recent expenses records.</td></tr>
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

<!-- Quick View Modal -->
<x-modal name="quick-view" :show="false" focusable>
    <div class="p-6" x-data="{ item: null }" x-on:open-quick-view.window="item = $event.detail">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl font-bold text-gray-900" x-text="item?.title || 'Details'"></h2>
            <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="space-y-4" x-show="item">
            <!-- Dynamic Content based on type -->
            <template x-if="item?.type === 'giving'">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 p-4 bg-brand-50 rounded-lg border border-brand-100 mb-2">
                        <p class="text-sm text-brand-600 font-medium uppercase">Amount</p>
                        <p class="text-3xl font-bold text-brand-700" x-text="item.amount"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Church</p>
                        <p class="font-medium text-gray-900" x-text="item.church"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Giving Type</p>
                        <p class="font-medium text-gray-900" x-text="item.category"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-medium text-gray-900" x-text="item.date"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Received By</p>
                        <p class="font-medium text-gray-900" x-text="item.user ?? 'System'"></p>
                    </div>
                </div>
            </template>

            <template x-if="item?.type === 'expense'">
                 <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2 p-4 bg-gray-100 rounded-lg border border-gray-200 mb-2">
                        <p class="text-sm text-gray-600 font-medium uppercase">Amount</p>
                        <p class="text-3xl font-bold text-gray-800" x-text="item.amount"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Church</p>
                        <p class="font-medium text-gray-900" x-text="item.church"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="font-medium text-gray-900" x-text="item.category"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Description</p>
                        <p class="font-medium text-gray-900" x-text="item.description || 'N/A'"></p>
                    </div>
                     <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="font-medium text-gray-900" x-text="item.date"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </div>
</x-modal>

<!-- Trigger Script -->
<script>
    function openQuickView(data) {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'quick-view' }));
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('open-quick-view', { detail: data }));
        }, 10);
    }
</script>
