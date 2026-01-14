<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('Dashboard Overview') }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">Welcome back, {{ Auth::user()->name }}. Here's the latest diocese overview.</p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                     <span class="inline-flex rounded-md shadow-sm">
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                             <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Generate Report
                        </button>
                    </span>
                    <span class="inline-flex rounded-md shadow-sm">
                         <a href="{{ route('activity-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors shadow-sm">
                             <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                             </svg>
                            View Logs
                        </a>
                    </span>
                </div>
            </div>

            <!-- Summary Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                <!-- Total Income (Blue Gradient) -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-4 -mb-4 h-24 w-24 rounded-full bg-black opacity-10 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">Total Income</p>
                                <p class="mt-2 text-3xl font-bold">{{ number_format($totalIncome) }} <span class="text-lg font-normal text-blue-200">RWF</span></p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                         <div class="mt-4 flex items-center text-sm text-blue-100">
                             <span class="bg-blue-400/30 px-2 py-0.5 rounded text-xs font-semibold mr-2">YTD</span>
                             <span>Current Year</span>
                        </div>
                    </div>
                </div>

                <!-- Total Expenses (Red/Pink Gradient) -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 p-6 shadow-lg text-white">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-4 -mb-4 h-24 w-24 rounded-full bg-black opacity-10 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-rose-100 uppercase tracking-wider">Total Expenses</p>
                                <p class="mt-2 text-3xl font-bold">{{ number_format($totalExpenses) }} <span class="text-lg font-normal text-rose-200">RWF</span></p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-rose-100">
                             <span class="bg-rose-400/30 px-2 py-0.5 rounded text-xs font-semibold mr-2">Approved</span>
                             <span>YTD</span>
                        </div>
                    </div>
                </div>

                <!-- Attendance (Purple Gradient) -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-lg text-white transition-transform hover:scale-[1.02] duration-300">
                    <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-4 -mb-4 h-24 w-24 rounded-full bg-black opacity-10 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-purple-100 uppercase tracking-wider">Total Attendance</p>
                                <p class="mt-2 text-3xl font-bold">{{ number_format($totalAttendance) }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm text-purple-100">
                             <span class="bg-purple-400/30 px-2 py-0.5 rounded text-xs font-semibold mr-2">AVG</span>
                             <span>Based on report</span>
                        </div>
                    </div>
                </div>

                <!-- Census (Teal/Emerald Gradient) -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 p-6 shadow-lg text-white transition-transform hover:scale-[1.02] duration-300">
                     <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-4 -mb-4 h-24 w-24 rounded-full bg-black opacity-10 blur-xl"></div>
                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-teal-100 uppercase tracking-wider">Total Population</p>
                                <p class="mt-2 text-3xl font-bold">{{ number_format($totalPopulation) }}</p>
                            </div>
                            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                         <div class="mt-4 flex items-center text-sm text-teal-100">
                             <span class="bg-teal-400/30 px-2 py-0.5 rounded text-xs font-semibold mr-2">LATEST</span>
                             <span>Census Data</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                             <h3 class="text-lg font-bold text-gray-900">Financial Trends</h3>
                             <p class="text-sm text-gray-500">Income vs Expenses Analysis</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="h-3 w-3 rounded-full bg-brand-600"></span>
                            <span class="text-xs text-gray-500">Income</span>
                            <span class="h-3 w-3 rounded-full bg-gray-300 ml-2"></span>
                            <span class="text-xs text-gray-500">Expenses</span>
                        </div>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Top Performing Churches -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 flex flex-col">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                         <div>
                            <h3 class="text-lg font-bold text-gray-900">Top Churches</h3>
                            <p class="text-xs text-gray-500 mt-1">Highest contributions this year</p>
                        </div>
                         <span class="text-xs font-semibold bg-green-100 text-green-700 py-1 px-2 rounded-full border border-green-200">
                            Leaderboard
                        </span>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 py-2">
                        <div class="divide-y divide-gray-100">
                            @foreach($topChurches as $index => $church)
                                <div class="flex items-center justify-between py-4 hover:bg-gray-50 rounded-lg transition-colors -mx-2 px-2">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10 relative">
                                            @if($index < 3)
                                                <div class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-yellow-400 border-2 border-white flex items-center justify-center text-[10px] font-bold text-yellow-900 shadow-sm">
                                                    {{ $index + 1 }}
                                                </div>
                                            @endif
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-brand-100 to-brand-200 flex items-center justify-center text-brand-700 font-bold border border-brand-200">
                                                {{ substr($church->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-3 truncate">
                                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $church->name }}</p>
                                            <p class="text-xs text-gray-500 flex items-center truncate">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                {{ $church->location }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right pl-4">
                                        <p class="text-sm font-bold text-gray-900">{{ number_format($church->givings_sum_amount) }}</p>
                                        <p class="text-xs text-gray-400">RWF</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
                        <a href="{{ route('churches.index') }}" class="inline-flex items-center text-sm text-brand-600 hover:text-brand-800 font-medium transition-colors">
                            View All Churches
                            <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Givings Table -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <div>
                             <h3 class="text-lg font-bold text-gray-900">Recent Givings</h3>
                             <p class="text-xs text-gray-500 mt-1">Latest incoming transactions</p>
                        </div>
                        <a href="{{ route('givings.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church & Type</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($recentGivings as $giving)
                                    <tr class="hover:bg-blue-50/50 transition cursor-pointer group" 
                                        onclick="openQuickView({
                                            type: 'giving',
                                            title: 'Giving Details',
                                            church: '{{ addslashes($giving->church?->name ?? 'N/A') }}',
                                            category: '{{ addslashes($giving->givingType?->name ?? 'N/A') }}',
                                            amount: '{{ number_format($giving->amount) }} RWF',
                                            date: '{{ $giving->created_at->format('M d, Y') }}',
                                            user: '{{ $giving->user ? addslashes($giving->user->name) : 'System' }}'
                                        })">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center mr-3 flex-shrink-0">
                                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-brand-600 transition-colors">{{ $giving->church?->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $giving->givingType?->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                +{{ number_format($giving->amount) }}
                                            </span>
                                        </td>
                                         <td class="px-6 py-4 text-nowrap text-right text-xs text-gray-500">
                                            {{ $giving->created_at->format('M d') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-10 text-sm text-gray-500 text-center italic">No records found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses Table -->
                 <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                         <div>
                             <h3 class="text-lg font-bold text-gray-900">Recent Expenses</h3>
                             <p class="text-xs text-gray-500 mt-1">Latest outgoing transactions</p>
                        </div>
                        <a href="{{ route('expenses.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-800 transition">View All</a>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Church & Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 bg-white">
                                @forelse($recentExpenses as $expense)
                                    <tr class="hover:bg-red-50/50 transition cursor-pointer group"
                                        onclick="openQuickView({
                                            type: 'expense',
                                            title: 'Expense Details',
                                            church: '{{ addslashes($expense->church?->name ?? 'N/A') }}',
                                            category: '{{ addslashes($expense->expenseCategory?->name ?? 'N/A') }}',
                                            amount: '{{ number_format($expense->amount) }} RWF',
                                            description: '{{ addslashes($expense->description ?? '') }}',
                                            date: '{{ $expense->created_at->format('M d, Y') }}'
                                        })">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded bg-red-100 text-red-600 flex items-center justify-center mr-3 flex-shrink-0">
                                                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-brand-600 transition-colors">{{ $expense->church?->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500">{{ $expense->expenseCategory?->name ?? 'N/A' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                -{{ number_format($expense->amount) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-nowrap text-right text-xs text-gray-500">
                                            {{ $expense->created_at->format('M d') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-10 text-sm text-gray-500 text-center italic">No records found.</td></tr>
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
                        backgroundColor: '#c81e1e', // Brand 700 (Red)
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                        hoverBackgroundColor: '#9b1c1c'
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyStats['expenses']),
                        backgroundColor: '#e5e7eb', // Gray 200
                        borderRadius: 6,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8,
                        hoverBackgroundColor: '#d1d5db'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 20,
                            font: {
                                family: 'Figtree',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleFont: { family: 'Figtree', size: 13 },
                        bodyFont: { family: 'Figtree', size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-RW', { style: 'currency', currency: 'RWF' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            font: { family: 'Figtree', size: 11 },
                            color: '#6b7280',
                            padding: 10,
                            callback: function(value) {
                                if (value >= 1000000) return (value/1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value/1000).toFixed(0) + 'k';
                                return value;
                            }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Figtree', size: 11 },
                            color: '#6b7280'
                        },
                        border: { display: false }
                    }
                }
            }
        });
    </script>
</x-app-layout>

<!-- Quick View Modal -->
<x-modal name="quick-view" :show="false" focusable>
    <div class="p-0 overflow-hidden rounded-2xl" x-data="{ item: null }" x-on:open-quick-view.window="item = $event.detail">
        <!-- Modal Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900" x-text="item?.title || 'Details'"></h2>
             <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500 transition focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6" x-show="item">
            <!-- Dynamic Content based on type -->
            <template x-if="item?.type === 'giving'">
                <div class="space-y-6">
                    <div class="text-center">
                         <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Transferred Amount</p>
                         <p class="text-4xl font-extrabold text-brand-600 mt-2" x-text="item.amount"></p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Church</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.church"></span>
                         </div>
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Giving Type</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.category"></span>
                         </div>
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Received By</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.user ?? 'System'"></span>
                         </div>
                         <div class="flex justify-between">
                             <span class="text-sm text-gray-500">Date</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.date"></span>
                         </div>
                    </div>
                </div>
            </template>

            <template x-if="item?.type === 'expense'">
                 <div class="space-y-6">
                    <div class="text-center">
                         <p class="text-sm text-gray-500 font-medium uppercase tracking-wide">Expense Amount</p>
                         <p class="text-4xl font-extrabold text-red-600 mt-2" x-text="item.amount"></p>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Church</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.church"></span>
                         </div>
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Category</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.category"></span>
                         </div>
                         <div class="flex justify-between border-b border-gray-200 pb-2">
                             <span class="text-sm text-gray-500">Date</span>
                             <span class="text-sm font-semibold text-gray-900" x-text="item.date"></span>
                         </div>
                          <div class="pt-2">
                             <span class="text-xs text-gray-400 block mb-1">Description</span>
                             <p class="text-sm text-gray-700 bg-white p-3 rounded border border-gray-200" x-text="item.description || 'N/A'"></p>
                         </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal Footer -->
        <div class="bg-gray-50 px-6 py-4 flex justify-end">
             <button x-on:click="$dispatch('close')" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition">
                Close Details
            </button>
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
