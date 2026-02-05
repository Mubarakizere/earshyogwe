@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Location</th>
                <th>Pastor</th>
                <th>Archdeacon</th>
                <th>Members</th>
            </tr>
        </thead>
        <tbody>
            @foreach($churches as $index => $church)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $church->name }}</td>
                <td>{{ $church->location ?? '-' }}</td>
                <td>{{ $church->pastor->name ?? '-' }}</td>
                <td>{{ $church->archid->name ?? '-' }}</td>
                <td class="text-center">{{ $church->members_count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
