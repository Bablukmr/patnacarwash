@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            Booking Details #{{ $booking->id }}
        </div>
        <div class="card-body">
            <h5>Customer Information</h5>
            <p>Name: {{ $booking->name }}<br>
                Email: {{ $booking->email }}<br>
                Phone: {{ $booking->phone }}</p>

            <h5>Service Details</h5>
            <p>Car Model: {{ $booking->car_model }}<br>
                Service Type: {{ $booking->service_type }}<br>
                Preferred Date: {{ $booking->preferred_date }}<br>
                Address: {{ $booking->address }}</p>

            @if($booking->workAssignment)
            <h5>Work Assignment</h5>
            <div class="row">
                <div class="col-md-6">
                    <p>Employee: {{ $booking->workAssignment->employee->name }}<br>
                        Status: {{ $booking->workAssignment->status }}<br>
                        Assigned On: {{ $booking->workAssignment->created_at }}<br>
                        Last Updated: {{ $booking->workAssignment->updated_at }}</p>

                    <h6>Location Details</h6>
                    <p>
                        Latitude: {{ $booking->workAssignment->latitude ?? 'N/A' }}<br>
                        Longitude: {{ $booking->workAssignment->longitude ?? 'N/A' }}<br>
                        Contact Number: {{ $booking->workAssignment->contact_number ?? 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <h6>Notes & Defects</h6>
                    <p>Notes: {{ $booking->workAssignment->notes ?? 'N/A' }}<br>
                        Defects: {{ $booking->workAssignment->defects ?? 'N/A' }}</p>
                </div>
            </div>

            <h5>Uploaded Images</h5>
            <div class="row">
                @if($booking->workAssignment->images)
                @php
                $images = json_decode($booking->workAssignment->images);
                @endphp
                @foreach($images as $image)
                <div class="col-md-3 mb-3">
                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail" alt="Work Image">
                </div>
                @endforeach
                @else
                <div class="col-12">
                    <p>No images uploaded</p>
                </div>
                @endif
            </div>

            @if($booking->workAssignment->live_camera)
            <h5>Live Camera Image</h5>
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('storage/'. $booking->workAssignment->live_camera) }}" class="img-thumbnail" alt="Live Camera Image">
                </div>
            </div>
            @endif

            <h5>Daily Updates</h5>
            <ul class="list-group">
                @foreach($booking->workAssignment->dailyUpdates as $update)
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>{{ $update->created_at->format('M d, Y H:i') }}</strong><br>
                            Status: {{ $update->cleaning_status }}
                        </div>
                        <div class="col-md-8">
                            Notes: {{ $update->daily_notes }}<br>
                            Defects: {{ $update->defects_found ?? 'N/A' }}
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <div class="alert alert-warning">No work assignment yet</div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .img-thumbnail {
        max-width: 100%;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    .list-group-item {
        padding: 1rem;
        border-left: 4px solid #007bff;
        /* Nice visual indicator */
    }
</style>
@endsection