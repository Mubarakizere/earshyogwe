<x-app-layout>
    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="mt-2 text-sm text-gray-500">Welcome back, {{ Auth::user()->name }}. Role: <span class="font-semibold text-brand-600">{{ ucfirst($userRole) }}</span></p>
            </div>

            @if(!$hasData)
                <!-- No Permission State -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Data Available</h3>
                    <p class="mt-2 text-sm text-gray-500">Your role doesn't have permissions to view dashboard data yet.<br>Contact your administrator to assign permissions.</p>
                </div>
            @else
                <!-- Summary Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
                    @isset($totalIncome)
                        <!-- Total Income -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg text-white">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-blue-100 uppercase tracking-wider">Total Income</p>
                                        <p class="mt-2 text-3xl font-bold">{{ number_format($totalIncome) }} <span class="text-lg font-normal text-blue-200">RWF</span></p>
                                    </div>
                                    <div class="p-3 bg-white/20 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset

                    @isset($totalExpenses)
                        <!-- Total Expenses -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 p-6 shadow-lg text-white">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-rose-100 uppercase tracking-wider">Total Expenses</p>
                                        <p class="mt-2 text-3xl font-bold">{{ number_format($totalExpenses) }} <span class="text-lg font-normal text-rose-200">RWF</span></p>
                                    </div>
                                    <div class="p-3 bg-white/20 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset

                    @isset($totalAttendance)
                        <!-- Attendance -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 p-6 shadow-lg text-white">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-purple-100 uppercase tracking-wider">Total Attendance</p>
                                        <p class="mt-2 text-3xl font-bold">{{ number_format($totalAttendance) }}</p>
                                    </div>
                                    <div class="p-3 bg-white/20 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset

                    @isset($totalPopulation)
                        <!-- Population -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 p-6 shadow-lg text-white">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-teal-100 uppercase tracking-wider">Total Population</p>
                                        <p class="mt-2 text-3xl font-bold">{{ number_format($totalPopulation) }}</p>
                                    </div>
                                    <div class="p-3 bg-white/20 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset

                    @isset($totalConverts)
                        <!-- Evangelism Converts -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 p-6 shadow-lg text-white">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-amber-100 uppercase tracking-wider">Total Converts</p>
                                        <p class="mt-2 text-3xl font-bold">{{ number_format($totalConverts) }}</p>
                                    </div>
                                    <div class="p-3 bg-white/20 rounded-lg">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endisset
                </div>

                <!-- Charts and Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    @isset($monthlyStats)
                        <!-- Financial Chart -->
                        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 p-6">
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-gray-900">Financial Trends</h3>
                                <p class="text-sm text-gray-500">Income vs Expenses</p>
                            </div>
                            <div class="h-64">
                                <canvas id="financialChart"></canvas>
                            </div>
                        </div>
                    @endisset

                    @isset($churches)
                        <!-- Churches List -->
                        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-lg font-bold text-gray-900">Churches</h3>
                            </div>
                            <div class="p-6">
                                @foreach($churches as $church)
                                    <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                                        <span class="text-sm font-semibold text-gray-900">{{ $church->name }}</span>
                                        <span class="text-sm text-gray-600">{{ number_format($church->givings_sum_amount ?? 0) }} RWF</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endisset
                </div>

                <!-- Recent Transactions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                   @isset($recentGivings)
                        <!-- Recent Givings -->
                        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-lg font-bold text-gray-900">Recent Givings</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Church</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 bg-white">
                                        @forelse($recentGivings as $giving)
                                            <tr class="hover:bg-blue-50/50">
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $giving->church->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-semibold text-green-600">+{{ number_format($giving->amount) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-6 py-10 text-sm text-gray-500 text-center">No records</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endisset

                    @isset($recentExpenses)
                        <!-- Recent Expenses -->
                        <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-lg font-bold text-gray-900">Recent Expenses</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Church</th>
                                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 bg-white">
                                        @forelse($recentExpenses as $expense)
                                            <tr class="hover:bg-red-50/50">
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $expense->church->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 text-right text-sm font-semibold text-red-600">-{{ number_format($expense->amount) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="px-6 py-10 text-sm text-gray-500 text-center">No records</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endisset
                </div>
            @endif
        </div>
    </div>

    @isset($monthlyStats)
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('financialChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthlyStats['labels']),
                    datasets: [
                        {
                            label: 'Income',
                            data: @json($monthlyStats['income']),
                            backgroundColor: '#c81e1e',
                            borderRadius: 6
                        },
                        {
                            label: 'Expenses',
                            data: @json($monthlyStats['expenses']),
                            backgroundColor: '#e5e7eb',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        </script>
    @endisset
</x-app-layout>
