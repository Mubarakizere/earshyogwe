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
        <div class="summary-title" style="color: #57534e;">MEMBERSHIP OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total'] ?? 0) }}</span>
                <span class="stat-label">Total Members</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #1e3a8a;">{{ number_format($stats['male'] ?? 0) }}</span>
                <span class="stat-label">Male</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #be185d;">{{ number_format($stats['female'] ?? 0) }}</span>
                <span class="stat-label">Female</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #059669;">{{ number_format($stats['baptized'] ?? 0) }}</span>
                <span class="stat-label">Baptized</span>
            </div>
        </div>
    </div>
    @endif

    <table style="border-top: 2px solid #57534e;">
        <thead>
            <tr>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">#</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Member ID</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Name</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Gender/Age</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Location</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Details</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="font-weight: bold; color: #4338ca; font-family: monospace; font-size: 9px;">
                    {{ $member->member_id ?? 'N/A' }}
                </td>
                <td style="font-weight: bold; color: #1c1917;">
                    {{ $member->name }}
                    <div style="font-size: 8px; color: #78716c; font-weight: normal; margin-top: 2px;">
                        {{ $member->marital_status }}
                    </div>
                </td>
                <td style="color: #44403c;">
                    {{ $member->sex }}
                    @if($member->age)
                        <span style="color: #78716c; font-size: 8px;">({{ $member->age }} yrs)</span>
                    @endif
                </td>
                <td style="color: #44403c;">
                    {{ $member->church->name ?? '-' }}
                    @if($member->chapel)
                        <div style="font-size: 8px; color: #78716c;">{{ $member->chapel }}</div>
                    @endif
                </td>
                <td style="color: #44403c; font-size: 8px;">
                     Baptism: {{ $member->baptism_status }}
                     @if($member->education_level)
                        <br>Ed: {{ $member->education_level }}
                     @endif
                </td>
                <td class="text-center">
                    @php
                        $status = $member->status ?? 'active';
                    @endphp
                    @if($status == 'active')
                         <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Active</span>
                    @elseif($status == 'inactive')
                         <span class="badge" style="background-color: #fef9c3; color: #854d0e; border: 1px solid #fde047;">Inactive</span>
                    @elseif($status == 'deceased')
                         <span class="badge" style="background-color: #f3f4f6; color: #1f2937; border: 1px solid #e5e7eb;">Deceased</span>
                    @else
                         <span class="badge" style="background-color: #f5f5f4; color: #78716c; border: 1px solid #e7e5e4;">{{ ucfirst($status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
