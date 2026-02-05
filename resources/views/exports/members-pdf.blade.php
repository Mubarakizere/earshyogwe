@extends('exports.pdf-layout')

@section('content')
    @if(isset($stats))
    <div class="summary-box">
        <span class="stat">
            <span class="stat-value">{{ number_format($stats['total'] ?? 0) }}</span>
            <span class="stat-label">Total</span>
        </span>
        <span class="stat">
            <span class="stat-value">{{ number_format($stats['male'] ?? 0) }}</span>
            <span class="stat-label">Male</span>
        </span>
        <span class="stat">
            <span class="stat-value">{{ number_format($stats['female'] ?? 0) }}</span>
            <span class="stat-label">Female</span>
        </span>
        <span class="stat">
            <span class="stat-value">{{ number_format($stats['baptized'] ?? 0) }}</span>
            <span class="stat-label">Baptized</span>
        </span>
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Church</th>
                <th>Chapel</th>
                <th>Marital Status</th>
                <th>Baptism</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $index => $member)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->sex }}</td>
                <td>{{ $member->age ?? '-' }}</td>
                <td>{{ $member->church->name ?? '-' }}</td>
                <td>{{ $member->chapel ?? '-' }}</td>
                <td>{{ $member->marital_status }}</td>
                <td>{{ $member->baptism_status }}</td>
                <td>
                    @php
                        $status = $member->status ?? 'active';
                    @endphp
                    <span class="badge {{ $status == 'active' ? 'badge-green' : ($status == 'inactive' ? 'badge-yellow' : 'badge-gray') }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
