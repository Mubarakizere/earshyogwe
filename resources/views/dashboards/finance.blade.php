<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Finance Dashboard (Diocese)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pending Actions -->
            @if($pendingExpenses > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                There are <span class="font-bold">{{ $pendingExpenses }}</span> expenses pending your review.
                                <a href="{{ route('expenses.index', ['status' => 'pending']) }}" class="font-medium underline hover:text-yellow-800">View Pending Expenses &rarr;</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500">Total Income (YTD)</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalIncome) }} RWF</p>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500">Total Expenses (YTD)</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalExpenses) }} RWF</p>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-xl p-6 border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Net Balance</p>
                    <p class="text-2xl font-bold {{ $netBalance >= 0 ? 'text-blue-800' : 'text-red-600' }}">{{ number_format($netBalance) }} RWF</p>
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

                <!-- Recent Transactions -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Latest Transactions</h3>
                    <div class="space-y-4">
                        @foreach($recentGivings->take(3) as $giving)
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <div><div class="font-medium text-green-600">+ {{ number_format($giving->amount) }}</div><div class="text-xs text-gray-500">{{ $giving->church->name }}</div></div>
                                <div class="text-gray-400 text-xs">{{ $giving->created_at->format('M d') }}</div>
                            </div>
                        @endforeach
                        @foreach($recentExpenses->take(3) as $expense)
                            <div class="flex justify-between items-center text-sm border-b pb-2">
                                <div><div class="font-medium text-red-600">- {{ number_format($expense->amount) }}</div><div class="text-xs text-gray-500">{{ $expense->church->name }}</div></div>
                                <div class="text-gray-400 text-xs">{{ $expense->created_at->format('M d') }}</div>
                            </div>
                        @endforeach
                        <div class="mt-4 text-center">
                            <a href="{{ route('expenses.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Manage Finances &rarr;</a>
                        </div>
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
