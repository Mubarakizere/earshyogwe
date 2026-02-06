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
        <div class="summary-title" style="color: #57534e;">REVENUE OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total_count'] ?? 0) }}</span>
                <span class="stat-label">Transactions</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #15803d;">{{ number_format($stats['total_amount'] ?? 0) }} RWF</span>
                <span class="stat-label">Total Revenue</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #0369a1;">{{ number_format($stats['sent_count'] ?? 0) }}</span>
                <span class="stat-label">Transfers Sent</span>
            </div>
        </div>
    </div>
    @endif

    <table style="border-top: 2px solid #57534e;">
        <thead>
            <tr>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">#</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Date</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Church</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Revenue Type</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-right">Amount</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($givings as $index => $giving)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="color: #44403c;">{{ $giving->date ? $giving->date->format('Y-m-d') : '-' }}</td>
                <td style="font-weight: bold; color: #1c1917;">{{ $giving->church->name ?? '-' }}</td>
                <td style="color: #44403c;">
                    {{ $giving->givingType->name ?? '-' }}
                    @if($giving->givingSubType)
                        <div style="font-size: 8px; color: #78716c;">{{ $giving->givingSubType->name }}</div>
                    @endif
                </td>
                <td class="text-right" style="color: #1c1917; font-family: monospace;">{{ number_format($giving->amount, 0) }} RWF</td>
                <td class="text-center">
                    @if($giving->receipt_status == 'verified')
                        <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Verified</span>
                    @elseif($giving->is_sent)
                        <span class="badge" style="background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;">Sent</span>
                    @else
                        <span class="badge" style="background-color: #fef9c3; color: #854d0e; border: 1px solid #fde047;">Pending</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #fafaf9; font-weight: bold; border-top: 2px solid #57534e;">
                <td colspan="4" class="text-right" style="color: #57534e; padding-right: 15px;">TOTAL REVENUE:</td>
                <td class="text-right" style="color: #1c1917; border-bottom: 3px double #57534e;">{{ number_format($stats['total_amount'] ?? $givings->sum('amount'), 0) }} RWF</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
