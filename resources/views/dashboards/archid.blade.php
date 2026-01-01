<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Regional Dashboard (Archid)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm font-medium text-gray-500">Regional Income</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalIncome) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm font-medium text-gray-500">Regional Expenses</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalExpenses) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm font-medium text-gray-500">Net Balance</p>
                    <p class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-gray-900' : 'text-red-600' }}">{{ number_format($netBalance) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm font-medium text-gray-500">Total Attendance</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAttendance) }}</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm rounded-xl p-6 border border-gray-100 hover:shadow-md transition">
                    <p class="text-sm font-medium text-gray-500">Population</p>
                    <p class="text-2xl font-bold text-brand-700">{{ number_format($totalPopulation) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Regional Financial Overview</h3>
                    <div class="relative h-80">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Assigned Churches List -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">My Assigned Churches</h3>
                    <div class="space-y-4">
                        @foreach($myChurches as $church)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $church->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $church->location }}</p>
                                </div>
                                <span class="font-bold text-green-600">{{ number_format($church->givings_sum_amount) }} RWF</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Recent Givings -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Givings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentGivings as $giving)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs">{{ $giving->church->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-green-600">{{ number_format($giving->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-3 text-sm text-gray-500 text-center">No recent givings</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Expenses -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Expenses</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Church</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentExpenses as $expense)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-600 truncate max-w-xs">{{ $expense->church->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-red-600">{{ number_format($expense->amount) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-3 text-sm text-gray-500 text-center">No recent expenses</td></tr>
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
                        backgroundColor: 'rgba(34, 197, 94, 0.6)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyStats['expenses']),
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
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
