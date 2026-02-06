@extends('exports.pdf-layout')

@push('styles')
<style>
    /* "Less AI", more Classic/Organic Theme (Sage & Slate) - Same as Parishes */
    header {
        border-bottom: 2px solid #57534e !important; /* Stone 600 */
        background-color: #fafaf9 !important; /* Stone 50 */
    }
    h1 {
        color: #44403c !important; /* Stone 700 */
        font-family: 'Georgia', serif;
        letter-spacing: 0.5px;
    }
    .summary-title {
        color: #57534e !important;
        font-family: 'Georgia', serif;
        border-bottom-style: double !important;
    }
    th {
        background-color: #57534e !important;
        border-color: #57534e !important;
    }
</style>
@endpush

@section('content')
    <!-- Summary Section - Classic & Clean -->
    @if(isset($stats))
    <div class="summary-box" style="background-color: #fafaf9; border: 1px solid #d6d3d1;">
        <div class="summary-title" style="color: #57534e;">OBJECTIVES OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #15803d;">{{ number_format($stats['completed'] ?? 0) }}</span>
                <span class="stat-label">Completed</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #ca8a04;">{{ number_format($stats['in_progress'] ?? 0) }}</span>
                <span class="stat-label">In Progress</span>
            </div>
        </div>
    </div>
    @endif

    <table style="border-top: 2px solid #57534e;">
        <thead>
            <tr>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">#</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Objective</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Church</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Date Range</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Progress</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objectives as $index => $objective)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="font-weight: bold; color: #1c1917;">
                    {{ $objective->name }}
                    <div style="font-size: 8px; color: #78716c; font-weight: normal; margin-top: 2px;">
                        {{ Str::limit($objective->description ?? '-', 40) }}
                    </div>
                </td>
                <td style="color: #44403c;">{{ $objective->church->name ?? '-' }}</td>
                <td style="color: #44403c; font-size: 8px;">
                    {{ $objective->start_date ? $objective->start_date->format('d/m/Y') : '' }}
                    <br>to<br>
                    {{ $objective->end_date ? $objective->end_date->format('d/m/Y') : '-' }}
                </td>
                <td style="color: #44403c;">
                    {{ $objective->current_progress ?? 0 }}%
                    <div style="font-size: 8px; color: #78716c;">Target: {{ $objective->target }} {{ $objective->target_unit }}</div>
                </td>
                <td class="text-center">
                    @php
                        $status = $objective->status ?? 'in_progress';
                    @endphp
                    @if($status == 'completed')
                        <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">{{ ucfirst($status) }}</span>
                    @elseif($status == 'in_progress')
                        <span class="badge" style="background-color: #fef9c3; color: #854d0e; border: 1px solid #fde047;">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                    @elseif($status == 'cancelled')
                        <span class="badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">{{ ucfirst($status) }}</span>
                    @else
                        <span class="badge" style="background-color: #f5f5f4; color: #78716c; border: 1px solid #e7e5e4;">{{ ucfirst($status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
