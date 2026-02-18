{{-- Progress Timeline Component --}}
<div x-data="{ progressModalOpen: false }" @keydown.escape.window="progressModalOpen = false">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-bold text-gray-900">Activity Reports List</h4> <!-- Was Progress Timeline -->
            @can('log activity progress')
                <button type="button" @click="progressModalOpen = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Add New Activity <!-- Was Log Progress -->
                    </span>
                </button>
            @endcan
        </div>

        @if($activity->progressLogs && $activity->progressLogs->count() > 0)
            {{-- Progress Chart --}}
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <canvas id="progressChart" style="max-height: 200px;"></canvas>
            </div>

            {{-- Timeline List --}}
            <div class="space-y-6">
                @foreach($activity->progressLogs as $log)
                    <div class="relative pl-8 pb-6 border-l-2 border-purple-200 last:border-l-0 last:pb-0">
                        {{-- Date badge on timeline --}}
                        <div class="absolute -left-3 top-0 w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition">
                            {{-- Header with date and percentage --}}
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ $log->log_date->format('M d, Y') }}
                                    </span>
                                    @if($log->progress_percentage > 100)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-bold rounded-full">
                                            100% 🌟
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm font-bold rounded-full">
                                            {{ $log->progress_percentage }}%
                                        </span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Cumulative Total</div>
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ number_format($log->cumulative_total) }} / {{ number_format($activity->target) }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $activity->target_unit ?? 'units' }}</div>
                                </div>
                            </div>

                            {{-- Period amount added --}}
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="bg-white px-3 py-2 rounded border border-purple-200">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 uppercase">Quantity/Output</span>
                                        <span class="text-lg font-bold text-purple-600">+{{ number_format($log->progress_value) }} {{ $activity->target_unit ?? 'units' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white px-3 py-2 rounded border border-purple-200">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 uppercase">Budget Spent</span>
                                        <span class="text-lg font-bold text-gray-700">{{ number_format($log->financial_spent) }} RWF</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Location --}}
                            @if($log->location)
                                <div class="flex items-center text-sm text-gray-600 mb-2">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $log->location }}
                                </div>
                            @endif

                            {{-- Progress bar --}}
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                @if($log->progress_percentage > 100)
                                    <div class="bg-gradient-to-r from-emerald-400 via-green-500 to-yellow-400 h-2 rounded-full transition-all animate-pulse" style="width: 100%"></div>
                                @else
                                    <div class="bg-purple-600 h-2 rounded-full transition-all" style="width: {{ $log->progress_percentage }}%"></div>
                                @endif
                            </div>

                            {{-- Activities Performed --}}
                            @if($log->activities_performed)
                                <div class="mb-3">
                                    <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Activities Performed</h5>
                                    <p class="text-sm text-gray-800 bg-white p-2 rounded border border-gray-100">{{ $log->activities_performed }}</p>
                                </div>
                            @endif

                            {{-- Results (Outcome) --}}
                            @if($log->results_outcome)
                                <div class="mb-3">
                                    <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Results (Outcome)</h5>
                                    <p class="text-sm text-gray-800 bg-white p-2 rounded border border-gray-100">{{ $log->results_outcome }}</p>
                                </div>
                            @endif

                            {{-- Notes --}}
                            @if($log->notes)
                                <div class="mb-3">
                                    <h5 class="text-xs font-bold text-gray-500 uppercase mb-1">Notes</h5>
                                    <p class="text-sm text-gray-600 italic">{{ $log->notes }}</p>
                                </div>
                            @endif

                            {{-- Photos --}}
                            @if($log->photos && count($log->photos) > 0)
                                <div class="flex gap-2 flex-wrap mb-3">
                                    @foreach($log->photos as $photo)
                                        <button type="button" 
                                            @click="$dispatch('open-document-modal', { url: '{{ Storage::url($photo) }}', type: 'image' })"
                                            class="relative w-24 h-24 rounded-lg overflow-hidden hover:opacity-90 transition border-2 border-gray-200 hover:border-purple-400">
                                            <img src="{{ Storage::url($photo) }}" alt="Progress photo" class="w-full h-full object-cover">
                                        </button>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Footer with logger info --}}
                            <div class="text-xs text-gray-500 flex items-center gap-2 pt-2 border-t border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Logged by {{ $log->logger->name }} on {{ $log->created_at->format('M d, Y @ g:i A') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <p class="text-gray-500 text-sm">No specific activities logged yet</p>
                <p class="text-gray-400 text-xs mt-1">Click "Log Progress" to add a report</p>
            </div>
        @endif
    </div>

    {{-- Progress Chart Script --}}
    @if($activity->progressLogs && $activity->progressLogs->count() > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('progressChart');
        if (ctx) {
            const logs = @json($activity->progressLogs->reverse()->values());
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: logs.map(log => new Date(log.log_date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'})),
                    datasets: [{
                        label: 'Progress',
                        data: logs.map(log => log.progress_percentage),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endif

    {{-- Log Progress Modal --}}
    <div x-show="progressModalOpen"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="progressModalOpen" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="progressModalOpen = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="progressModalOpen"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('activities.progress.add', $activity) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="mb-4 text-center">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 border-b pb-2">Activity Reporting Form</h3>
                        <p class="text-sm text-gray-500 mt-2">Fill in the details for {{ strtolower($activity->tracking_frequency) }} report.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                                <input type="date" name="log_date" required value="{{ date('Y-m-d') }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Budget Spent (RWF)</label>
                                <input type="number" name="financial_spent" min="0" value="0"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Activities Performed <span class="text-red-500">*</span></label>
                            <textarea name="activities_performed" rows="3" required placeholder="Describe specific activities done..."
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Results (Outcome)</label>
                            <textarea name="results_outcome" rows="3" placeholder="What was the result/outcome?"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantity/Output <span class="text-red-500">*</span></label>
                                <input type="number" name="progress_value" required min="0" value="0"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-1">Target: {{ number_format($activity->target) }} {{ $activity->target_unit }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" name="location" placeholder="Where did this happen?"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">General Notes</label>
                            <textarea name="notes" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Upload Photos (Optional)</label>
                            <input type="file" name="photos[]" multiple accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-purple-50 file:text-purple-700
                                    hover:file:bg-purple-100">
                        </div>
                    </div>

                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:col-start-2 sm:text-sm">
                            Submit Report
                        </button>
                        <button type="button" @click="progressModalOpen = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
