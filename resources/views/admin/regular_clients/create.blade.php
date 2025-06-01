@extends('admin.layout')

@section('content')

<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Assign Regular Client</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('regular-clients-list.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Client</label>
                    <select name="client_id" class="form-control" required>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">Name:{{ $client->name }} & Email:{{ $client->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Employee</label>
                    <select name="employee_id" class="form-control" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">Name:{{ $employee->name }} & Email:{{ $employee->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" placeholder="Enter location">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="number" name="contact_number"  class="form-control" placeholder="Enter contact number">
                </div>
                <button type="submit" class="btn btn-primary">Assign Client</button>
            </form>
        </div>
    </div>
</div>
@endsection