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
        <div class="summary-title" style="color: #57534e;">DIRECTORATE OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #15803d;">{{ number_format($stats['active'] ?? 0) }}</span>
                <span class="stat-label">Active</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #78716c;">{{ number_format($stats['inactive'] ?? 0) }}</span>
                <span class="stat-label">Inactive</span>
            </div>
        </div>
    </div>
    @endif

    <table style="border-top: 2px solid #57534e;">
        <thead>
            <tr>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">#</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Directorates Name</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Church</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Description</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $index => $department)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="font-weight: bold; color: #1c1917;">{{ $department->name }}</td>
                <td style="color: #44403c;">{{ $department->church->name ?? 'Global (All Churches)' }}</td>
                <td style="color: #44403c;">{{ Str::limit($department->description ?? '-', 60) }}</td>
                <td class="text-center">
                    @if($department->is_active)
                        <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Active</span>
                    @else
                        <span class="badge" style="background-color: #f5f5f4; color: #78716c; border: 1px solid #e7e5e4;">Inactive</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
