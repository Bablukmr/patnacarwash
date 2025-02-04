@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <div class="card-body">
    <h2>Assign Work for Booking Name: {{ $booking->id }} ,Id: {{ $booking->id }}</h2>
    
    <form method="POST" action="{{ route('admin.assign-work.store', $booking) }}">
        @csrf
        
        <div class="form-group">
            <label>Select Employee:</label>
            <select name="employee_id" class="form-control select2" style="width: 100%;">
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">
                        Name: {{ $employee->name }} ,Email: {{ $employee->email }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Assign Work</button>
    </form></div>
</div>
@endsection

@section('extraCss')
@endsection

@section('extraJs')
<!-- Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function () {
    // Initialize Select2 for Employee selection dropdown
    $('.select2').select2({
      placeholder: 'Select an employee', // Optional placeholder
      theme: 'bootstrap4', // Use Bootstrap 4 theme for styling
      width: '100%', // Set width to 100%
    });
  });
</script>
@endsection
