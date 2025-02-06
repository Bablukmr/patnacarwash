@extends('employee.layout')

@section('content')
<div class="content-wrapper">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Capture Image from Camera</h2>
        @include('message')

        <!-- Location Detection -->
        <div class="alert" id="locationAlert" role="alert">
            <span id="locationStatus">Detecting your location...</span>
        </div>

        <form method="POST" action="{{ route('teacher.update-work.put', $assignment->id) }}" id="submitForm" enctype="multipart/form-data" class="form-horizontal">
            @csrf
            @method('PUT')

            <!-- Location fields -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div class="form-group row">
                <label for="status" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    <select name="status" id="status" class="form-control" required>
                        <option value="in_progress" {{ $assignment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $assignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="notes" class="col-sm-2 col-form-label">Notes</label>
                <div class="col-sm-10">
                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $assignment->notes) }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="defects" class="col-sm-2 col-form-label">Defects</label>
                <div class="col-sm-10">
                    <textarea name="defects" id="defects" class="form-control">{{ old('defects', $assignment->defects) }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="cameraInput" class="col-sm-2 col-form-label">Capture Image (from Camera)</label>
                <div class="col-sm-10">
                    <!-- Video stream for live camera feed -->
                    <video id="videoElement" autoplay class="w-100 mb-3"></video>
                    <button type="button" id="captureButton" class="btn btn-primary btn-block">Capture Image</button>
                    <br><br>
                    <canvas id="canvas" style="display:none;"></canvas>
                    <div class="camera-preview mt-3"></div>
                </div>
            </div>

            <!-- Images field to store captured images -->
            <div class="form-group row">
                <label for="images" class="col-sm-2 col-form-label">Captured Images</label>
                <div class="col-sm-10">
                    <input type="file" name="images[]" id="images" class="form-control" multiple required>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-success btn-block" disabled>Submit</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationAlert = document.querySelector('#locationAlert');
    const locationStatus = document.getElementById('locationStatus');
    const submitBtn = document.getElementById('submitBtn');
    const captureButton = document.getElementById('captureButton');
    const videoElement = document.getElementById('videoElement');
    const canvas = document.getElementById('canvas');
    const cameraPreview = document.querySelector('.camera-preview');
    const submitForm = document.getElementById('submitForm');

    // Function to handle location errors
    function handleLocationError(error) {
        console.error('Geolocation error:', error);
        locationStatus.innerHTML = 'Location access is required to submit updates';
        locationAlert.classList.remove('alert-info');
        locationAlert.classList.add('alert-danger');
        submitBtn.disabled = true;
    }

    // Check if geolocation is available
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                // If location is successfully fetched
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                locationStatus.innerHTML = `Location captured: ${position.coords.latitude}, ${position.coords.longitude}`;
                locationAlert.classList.remove('alert-info');
                locationAlert.classList.add('alert-success');
                submitBtn.disabled = false;
            },
            error => {
                // Handle errors if geolocation fails
                handleLocationError(error);
            },
            {
                enableHighAccuracy: true,
                timeout: 5000, // Set a timeout to limit how long geolocation should wait
                maximumAge: 0
            }
        );
    } else {
        // Geolocation is not supported in the browser
        handleLocationError(new Error('Geolocation is not supported by this browser.'));
    }

    // Initialize camera on desktop or mobile devices
    function startCamera() {
        const constraints = {
            video: true
        };

        // Get user media for camera access
        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                videoElement.srcObject = stream;
            })
            .catch(function(err) {
                console.log('Camera error: ', err);
            });
    }

    // Start the camera if mobile or laptop
    startCamera();

    // Capture image from video stream
    captureButton.addEventListener('click', function () {
        const context = canvas.getContext('2d');
        canvas.width = videoElement.videoWidth;
        canvas.height = videoElement.videoHeight;
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        // Show captured image in preview
        const dataUrl = canvas.toDataURL('image/png');
        const img = document.createElement('img');
        img.src = dataUrl;
        img.classList.add('img-thumbnail');
        cameraPreview.innerHTML = ''; // Clear any previous image
        cameraPreview.appendChild(img);

        // Append the captured image to the form as a live_camera field
        const liveCameraField = document.createElement('input');
        liveCameraField.type = 'hidden';
        liveCameraField.name = 'live_camera';
        liveCameraField.value = dataUrl;  // Store the image data URL
        submitForm.appendChild(liveCameraField);
    });
});
</script>

@endsection
