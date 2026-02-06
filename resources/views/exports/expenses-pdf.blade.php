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
        <div class="summary-title" style="color: #57534e;">EXPENDITURE OVERVIEW</div>
        <div class="stat-grid">
            <div class="stat-item">
                <span class="stat-value" style="color: #44403c;">{{ number_format($stats['total_count'] ?? 0) }}</span>
                <span class="stat-label">Total Transactions</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #b91c1c;">{{ number_format($stats['total_amount'] ?? 0) }} RWF</span>
                <span class="stat-label">Total Expenses</span>
            </div>
            <div class="stat-item">
                <span class="stat-value" style="color: #ca8a04;">{{ number_format($stats['pending_count'] ?? 0) }}</span>
                <span class="stat-label">Pending Approval</span>
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
                <th style="background-color: #57534e; border-color: #57534e; color: white;">Category</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-right">Amount</th>
                <th style="background-color: #57534e; border-color: #57534e; color: white;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $index => $expense)
            <tr>
                <td style="color: #78716c;">{{ $index + 1 }}</td>
                <td style="color: #44403c;">{{ $expense->date->format('Y-m-d') }}</td>
                <td style="font-weight: bold; color: #1c1917;">{{ $expense->church->name ?? '-' }}</td>
                <td style="color: #44403c;">
                    {{ $expense->expenseCategory->name ?? '-' }}
                    <div style="font-size: 8px; color: #78716c; font-style: italic;">
                        {{ Str::limit($expense->description, 25) }}
                    </div>
                </td>
                <td class="text-right" style="color: #1c1917; font-family: monospace;">{{ number_format($expense->amount, 0) }} RWF</td>
                <td class="text-center">
                    @php
                        $status = $expense->status ?? 'pending';
                    @endphp
                    @if($status == 'approved')
                        <span class="badge" style="background-color: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;">Approved</span>
                    @elseif($status == 'rejected')
                        <span class="badge" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">Rejected</span>
                    @else
                        <span class="badge" style="background-color: #fef9c3; color: #854d0e; border: 1px solid #fde047;">Pending</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #fafaf9; font-weight: bold; border-top: 2px solid #57534e;">
                <td colspan="4" class="text-right" style="color: #57534e; padding-right: 15px;">TOTAL EXPENSES:</td>
                <td class="text-right" style="color: #b91c1c; border-bottom: 3px double #57534e;">{{ number_format($expenses->sum('amount'), 0) }} RWF</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
