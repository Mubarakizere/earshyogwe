{{-- Progress Timeline Component --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-lg font-bold text-gray-900">Progress Timeline</h4>
        @can('log activity progress')
            <button type="button" @click="progressModalOpen = true" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Log Progress
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
        <div class="space-y-4">
            @foreach($activity->progressLogs as $log)
                <div class="flex gap-4 pb-4 border-b border-gray-200 last:border-b-0">
                    <div class="flex-shrink-0 w-16 text-center">
                        <div class="text-xs text-gray-500">{{ $log->log_date->format('M d') }}</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $log->progress_percentage }}%</div>
                    </div>

                    <div class="flex-grow">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-gray-900">{{ number_format($log->progress_value) }} / {{ number_format($activity->target) }}</span>
                            <span class="text-xs text-gray-500">{{ $activity->target_unit ?? 'units' }}</span>
                        </div>

                        @if($log->notes)
                            <p class="text-sm text-gray-700 mb-2">{{ $log->notes }}</p>
                        @endif

                        @if($log->photos && count($log->photos) > 0)
                            <div class="flex gap-2 flex-wrap">
                                @foreach($log->photos as $photo)
                                    <button type="button" 
                                        @click="$dispatch('open-document-modal', { url: '{{ Storage::url($photo) }}', type: 'image' })"
                                        class="relative w-20 h-20 rounded overflow-hidden hover:opacity-90 transition">
                                        <img src="{{ Storage::url($photo) }}" alt="Progress photo" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <div class="text-xs text-gray-400 mt-2">
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
            <p class="text-gray-500 text-sm">No progress logs yet</p>
            <p class="text-gray-400 text-xs mt-1">Click "Log Progress" to add the first update</p>
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
                        beginAt Zero: true,
                        max: 100,
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
<div x-data="{ progressModalOpen: false }"
     @keydown.escape.window="progressModalOpen = false"
     x-show="progressModalOpen"
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
                <div class="mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Log Activity Progress</h3>
                    <p class="text-sm text-gray-500 mt-1">Track: {{ strtolower($activity->tracking_frequency) }}</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" name="log_date" required value="{{ date('Y-m-d') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Progress Value <span class="text-red-500">*</span></label>
                        <input type="number" name="progress_value" required min="0" value="{{ $activity->current_progress }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                        <p class="text-xs text-gray-500 mt-1">Current: {{ number_format($activity->current_progress) }} / Target: {{ number_format($activity->target) }} {{ $activity->target_unit }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="3"
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
                        <p class="text-xs text-gray-500 mt-1">Upload progress photos (max 5MB each)</p>
                    </div>
                </div>

                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:col-start-2 sm:text-sm">
                        Save Progress
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
