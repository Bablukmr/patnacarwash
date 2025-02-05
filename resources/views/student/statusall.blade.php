@extends('student.layout')

@section('content')
<div class="content-wrapper p-3">
    <h2>My Booking Status</h2>
    <div class="row">
        @foreach($bookings as $booking)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    Booking #{{ $booking->id }} - {{ $booking->car_model }}
                </div>
                <div class="card-body">
                    <h5 class="card-title">Service: {{ $booking->service_type }}</h5>
                    <p class="card-text">
                        Status: {{ $booking->workAssignment->status ?? 'Pending Assignment' }}<br>
                        @if($booking->workAssignment)
                        Assigned Employee: {{ $booking->workAssignment->employee->name }}<br>
                        Last Update: {{ $booking->workAssignment->dailyUpdates->last()->created_at ?? 'No updates yet' }}
                        @endif
                    </p>
                    <a href="{{ route('student.bookings') }}" class="btn btn-primary">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection