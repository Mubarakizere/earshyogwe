@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Church</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach($expenses as $index => $expense)
            @php $totalAmount += $expense->amount; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $expense->date->format('Y-m-d') }}</td>
                <td>{{ $expense->church->name ?? '-' }}</td>
                <td>{{ $expense->category->name ?? '-' }}</td>
                <td>{{ Str::limit($expense->description, 30) }}</td>
                <td class="text-right">{{ number_format($expense->amount, 0) }} RWF</td>
                <td>
                    @php
                        $status = $expense->status ?? 'pending';
                    @endphp
                    <span class="badge {{ $status == 'approved' ? 'badge-green' : ($status == 'rejected' ? 'badge-gray' : 'badge-yellow') }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="5" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($totalAmount, 0) }} RWF</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
