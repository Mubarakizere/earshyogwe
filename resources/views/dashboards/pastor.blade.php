<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Church Dashboard (Pastor)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('givings.create') }}" class="flex flex-col items-center justify-center bg-green-600 hover:bg-green-700 text-white rounded-xl p-4 shadow transition duration-150">
                    <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-sm font-semibold">Record Giving</span>
                </a>
                
                <a href="{{ route('expenses.create') }}" class="flex flex-col items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-xl p-4 shadow transition duration-150">
                    <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="text-sm font-semibold">Enter Expense</span>
                </a>

                <a href="{{ route('attendances.create') }}" class="flex flex-col items-center justify-center bg-purple-600 hover:bg-purple-700 text-white rounded-xl p-4 shadow transition duration-150">
                    <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm font-semibold">Mark Attendance</span>
                </a>

                <a href="{{ route('evangelism.create') }}" class="flex flex-col items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl p-4 shadow transition duration-150">
                    <svg class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-sm font-semibold">Evangelism Report</span>
                </a>
            </div>

            <!-- Pending Expense Alert -->
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
                                You have <span class="font-bold">{{ $pendingExpenses }}</span> expenses waiting for Archid/Boss approval.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">Income (Yearly)</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalIncome) }}</p>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">Expenses (Yearly)</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalExpenses) }}</p>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <p class="text-sm font-medium text-gray-500">Attendance (Yearly)</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($totalAttendance) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Financial Chart -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Overview</h3>
                    <div class="relative h-80">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Recent Activity Feed -->
                <div class="bg-white overflow-hidden shadow-xl rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($recentGivings as $giving)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last) <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span> @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Received <span class="font-medium text-gray-900">{{ number_format($giving->amount) }}</span></p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $giving->date }}">{{ \Carbon\Carbon::parse($giving->date)->format('M d') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                            @foreach($recentExpenses as $expense)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Spent <span class="font-medium text-gray-900">{{ number_format($expense->amount) }}</span></p>
                                                    <p class="text-xs text-gray-400">{{ Str::limit($expense->description, 20) }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $expense->date }}">{{ \Carbon\Carbon::parse($expense->date)->format('M d') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
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
            type: 'line', // Line chart for pastors
            data: {
                labels: @json($monthlyStats['labels']),
                datasets: [
                    {
                        label: 'Income',
                        data: @json($monthlyStats['income']),
                        borderColor: 'rgba(34, 197, 94, 1)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Expenses',
                        data: @json($monthlyStats['expenses']),
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4
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
