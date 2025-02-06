@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Regular Clients</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Start Date</th>
                        <th>Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regularClients as $client)
                    <tr>
                        <td>{{ $client->user->name }}</td>
                        <td>{{ $client->start_date->format('d M Y') }}</td>
                        <td>{{ $client->duration_months }} months</td>
                        <td>
                            <a href="{{ route('recurring-assignments.create', ['client' => $client->id]) }}"
                                class="btn btn-sm btn-primary">
                                Assign Schedule
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection