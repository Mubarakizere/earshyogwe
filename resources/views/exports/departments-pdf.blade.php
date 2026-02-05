@extends('exports.pdf-layout')

@section('content')
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Church</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $index => $department)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->church->name ?? '-' }}</td>
                <td>{{ Str::limit($department->description ?? '-', 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
