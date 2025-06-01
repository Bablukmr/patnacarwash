@extends('student.layout')

@section('content')
<div class="container">
    <h2>My Regular Booking</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Assigned By</th>
                <th>Location</th>
                <th>Contact Number</th>
            </tr>
        </thead>
        <tbody>
            @foreach($regularClients as $client)
            <tr>
                <td>{{ $client->employee->name }}</td>
                <td>{{ $client->assignedBy->name }}</td>
                <td>{{ $client->location }}</td>
                <td>{{ $client->contact_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection