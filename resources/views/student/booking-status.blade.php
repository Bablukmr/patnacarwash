@extends('student.layout')

@section('content')
<div class="content-wrapper">
    <h2 class="text-center my-4">Booking Status</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Booking #{{ $booking->id }} - {{ $booking->car_model }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5>
                                <strong>Status:</strong>
                                <span class="badge {{ optional($booking->workAssignment)->status ? 'bg-success' : 'bg-warning' }}">
                                    {{ optional($booking->workAssignment)->status ?? 'Pending Assignment' }}
                                </span>
                            </h5>
                            <p><strong>Service Type:</strong> {{ $booking->service_type }}</p>
                            <p><strong>Preferred Date:</strong> {{ $booking->preferred_date }}</p>
                            <p><strong>Preferred Time:</strong> {{ $booking->preferred_time }}</p>
                            <p><strong>Address:</strong> {{ $booking->address }}</p>
                        </div>
                        <div class="col-lg-6">
                            @if($booking->workAssignment)
                            <h5 class="mt-4">Work Assignment Details</h5>
                            <p><strong>Assigned Employee:</strong> {{ optional($booking->workAssignment->employee)->name ?? 'Not Assigned' }}</p>
                            <p><strong>Latitude:</strong> {{ $booking->workAssignment->latitude ?? 'N/A' }}</p>
                            <p><strong>Longitude:</strong> {{ $booking->workAssignment->longitude ?? 'N/A' }}</p>
                            <p><strong>Last Update:</strong>
                                {{ optional(optional($booking->workAssignment->dailyUpdates)->last())->created_at ?? 'No updates yet' }}
                            </p>
                            @else
                            <p><strong>Work Assignment:</strong> Not yet assigned</p>
                            @endif
                        </div>
                    </div>

                    @if($booking->workAssignment)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Images:</h5>
                            @foreach(optional($booking->workAssignment->dailyUpdates) ?? [] as $update)
                            @if($update->images)
                            @foreach(json_decode($update->images) as $image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Update Image" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                            </div>
                            @endforeach
                            @endif
                            @endforeach
                        </div>

                        <div class="col-md-6">
                            <h5>Live Camera Image:</h5>
                            @if($booking->workAssignment->live_camera)
                            <img src="{{ asset('storage/' . $booking->workAssignment->live_camera) }}" class="img-fluid rounded" alt="Live Camera Image" style="max-width: 100%; max-height: 300px; object-fit: cover;">
                            @else
                            <p>No live camera image available</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('student.bookings') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection