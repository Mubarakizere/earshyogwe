@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Church</th>
                <th>Target Date</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($objectives as $index => $objective)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $objective->title }}</td>
                <td>{{ $objective->church->name ?? '-' }}</td>
                <td>{{ $objective->target_date ? $objective->target_date->format('Y-m-d') : '-' }}</td>
                <td>{{ Str::limit($objective->description ?? '-', 40) }}</td>
                <td>
                    @php
                        $status = $objective->status ?? 'pending';
                    @endphp
                    <span class="badge {{ $status == 'approved' ? 'badge-green' : ($status == 'rejected' ? 'badge-gray' : 'badge-yellow') }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
