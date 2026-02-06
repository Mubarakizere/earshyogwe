@extends('exports.pdf-layout')

@push('styles')
<style>
    /* "Less AI", more Classic/Organic Theme (Sage & Slate) */
    header {
        border-bottom: 2px solid #57534e !important; /* Stone 600 */
        background-color: #fafaf9 !important; /* Stone 50 */
    }
    h1 {
        color: #44403c !important; /* Stone 700 */
        font-family: 'Georgia', serif; /* More classic feel */
        letter-spacing: 0.5px;
    }
    .summary-title {
        color: #57534e !important;
        font-family: 'Georgia', serif;
        border-bottom-style: double !important;
    }
    th {
        background-color: #57534e !important; /* Stone 600 */
        border-color: #57534e !important;
    }
</style>
@endpush

@section('content')
    <!-- Summary Section - Classic & Clean -->
    @if(isset($stats))
    <div class="summary-box" style="background-color: #fafaf9; border: 1px solid #d6d3d1;">
        <div class="summary-title" style="color: #57534e;">PARISH OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="stat-label">Total Parishes</span>
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

    <!-- Main Table -->
    <table style="border-top: 2px solid #57534e;">
        <thead>
            <tr>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">#</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Name</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Location</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Pastor</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Archdeacon</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Members</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($churches as $index => $church)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="font-weight: bold; color: #1c1917;">{{ $church->name }}</td>
                <td style="color: #44403c;">{{ $church->location ?? '-' }}</td>
                <td style="color: #44403c;">
                    @if($church->pastor)
                        {{ $church->pastor->name }}
                    @else
                        <span style="color: #a8a29e; font-style: italic;">Vacant</span>
                    @endif
                </td>
                <td style="color: #44403c;">{{ $church->archid->name ?? '-' }}</td>
                <td class="text-center">
                    @if($church->members_count > 0)
                        <span style="background-color: #f5f5f4; color: #44403c; padding: 2px 6px; border-radius: 4px; border: 1px solid #e7e5e4;">{{ $church->members_count }}</span>
                    @else
                        <span style="color: #d6d3d1;">-</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($church->is_active)
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
