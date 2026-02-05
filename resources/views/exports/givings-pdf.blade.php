@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Church</th>
                <th>Revenue Type</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @foreach($givings as $index => $giving)
            @php $totalAmount += $giving->amount; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $giving->date->format('Y-m-d') }}</td>
                <td>{{ $giving->church->name ?? '-' }}</td>
                <td>{{ $giving->givingType->name ?? '-' }}</td>
                <td class="text-right">{{ number_format($giving->amount, 0) }} RWF</td>
                <td>
                    <span class="badge {{ $giving->is_sent ? 'badge-green' : 'badge-yellow' }}">
                        {{ $giving->is_sent ? 'Sent' : 'Pending' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="4" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($totalAmount, 0) }} RWF</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection
