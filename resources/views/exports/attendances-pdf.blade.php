@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Church</th>
                <th>Service Type</th>
                <th>Men</th>
                <th>Women</th>
                <th>Youth</th>
                <th>Children</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->date->format('Y-m-d') }}</td>
                <td>{{ $attendance->church->name ?? '-' }}</td>
                <td>{{ $attendance->serviceType->name ?? '-' }}</td>
                <td class="text-center">{{ $attendance->men ?? 0 }}</td>
                <td class="text-center">{{ $attendance->women ?? 0 }}</td>
                <td class="text-center">{{ $attendance->youth ?? 0 }}</td>
                <td class="text-center">{{ $attendance->children ?? 0 }}</td>
                <td class="text-center"><strong>{{ ($attendance->men ?? 0) + ($attendance->women ?? 0) + ($attendance->youth ?? 0) + ($attendance->children ?? 0) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
