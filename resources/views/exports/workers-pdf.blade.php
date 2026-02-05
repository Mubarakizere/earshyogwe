@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Position</th>
                <th>Church</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workers as $index => $worker)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $worker->name }}</td>
                <td>{{ $worker->position ?? '-' }}</td>
                <td>{{ $worker->church->name ?? '-' }}</td>
                <td>{{ $worker->phone ?? '-' }}</td>
                <td>{{ $worker->email ?? '-' }}</td>
                <td>
                    @php
                        $status = $worker->status ?? 'active';
                    @endphp
                    <span class="badge {{ $status == 'active' ? 'badge-green' : 'badge-gray' }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
