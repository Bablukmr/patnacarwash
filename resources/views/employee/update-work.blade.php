@extends('employee.layout')

@section('content')
<div class="content-wrapper">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Capture Image from Camera</h2>
        @include('message')

        <!-- Location Detection -->
        <div class="alert" id="locationAlert" role="alert">
            <span id="locationStatus">Detecting your location...</span>
            <div id="addressDetails" class="mt-2 small"></div>
        </div>

        <form method="POST" action="{{ route('teacher.update-work.put', $assignment->id) }}" id="submitForm" enctype="multipart/form-data" class="form-horizontal">
            @csrf
            @method('PUT')

            <!-- Location fields -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <input type="hidden" id="full_address" name="full_address">
            <input type="hidden" id="pincode" name="pincode">

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
                <label class="col-sm-2 col-form-label">Camera Capture</label>
                <div class="col-sm-10">
                    <!-- Video stream for live camera feed -->
                    <video id="videoElement" autoplay class="w-100 mb-3 border rounded"></video>
                    <button type="button" id="captureButton" class="btn btn-primary btn-block">Capture Image with Location</button>
                    <br><br>
                    <canvas id="canvas" style="display:none;"></canvas>

                    <!-- Preview area with metadata -->
                    <div class="camera-preview mt-3">
                        <div class="card" style="display:none;" id="previewCard">
                            <img id="previewImage" class="card-img-top">
                            <div class="card-body">
                                <p class="card-text"><small class="text-muted" id="previewLocation"></small></p>
                                <p class="card-text"><small class="text-muted" id="previewAddress"></small></p>
                                <p class="card-text"><small class="text-muted" id="previewTime"></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden field for captured images -->
            <input type="hidden" name="live_camera" id="capturedImages">
            <!-- Images field to Upload  images -->
            <div class="form-group row">
                <label for="images" class="col-sm-2 col-form-label">Upload Images</label>
                <div class="col-sm-10">
                    <input type="file" name="images[]" id="images" class="form-control" multiple required>
                </div>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-success btn-block" disabled>Submit</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationAlert = document.querySelector('#locationAlert');
        const locationStatus = document.getElementById('locationStatus');
        const addressDetails = document.getElementById('addressDetails');
        const submitBtn = document.getElementById('submitBtn');
        const captureButton = document.getElementById('captureButton');
        const videoElement = document.getElementById('videoElement');
        const canvas = document.getElementById('canvas');
        const previewCard = document.getElementById('previewCard');
        const previewImage = document.getElementById('previewImage');
        const previewLocation = document.getElementById('previewLocation');
        const previewAddress = document.getElementById('previewAddress');
        const previewTime = document.getElementById('previewTime');
        const submitForm = document.getElementById('submitForm');

        let currentLocation = {};
        let currentAddress = "";
        let currentPincode = "";
        let capturedImages = [];

        // Function to get address details from coordinates
        async function getAddressDetails(latitude, longitude) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`);
                const data = await response.json();

                if (data.address) {
                    const address = data.address;
                    let fullAddress = "";

                    // Construct full address
                    if (address.road) fullAddress += `${address.road}, `;
                    if (address.neighbourhood) fullAddress += `${address.neighbourhood}, `;
                    if (address.suburb) fullAddress += `${address.suburb}, `;
                    if (address.city) fullAddress += `${address.city}, `;
                    if (address.state) fullAddress += `${address.state}, `;
                    if (address.country) fullAddress += `${address.country}`;

                    // Get pincode if available
                    const pincode = address.postcode || "Not available";

                    return {
                        fullAddress: fullAddress.trim().replace(/,$/, ''),
                        pincode: pincode,
                        addressDetails: address
                    };
                }
                return {
                    fullAddress: "Address not available",
                    pincode: "Not available",
                    addressDetails: {}
                };
            } catch (error) {
                console.error('Geocoding error:', error);
                return {
                    fullAddress: "Could not retrieve address",
                    pincode: "Not available",
                    addressDetails: {}
                };
            }
        }

        // Function to format current time
        function getCurrentTime() {
            const now = new Date();
            return now.toLocaleString();
        }

        // Function to draw text on canvas
        function drawTextOnCanvas(canvas, text, x, y, fontSize = 14) {
            const ctx = canvas.getContext('2d');
            ctx.font = `${fontSize}px Arial`;
            ctx.fillStyle = 'white';
            ctx.strokeStyle = 'black';
            ctx.lineWidth = 2;
            ctx.strokeText(text, x, y);
            ctx.fillText(text, x, y);
        }

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
                async position => {
                        // If location is successfully fetched
                        currentLocation = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };

                        document.getElementById('latitude').value = currentLocation.latitude;
                        document.getElementById('longitude').value = currentLocation.longitude;

                        // Get address details
                        const {
                            fullAddress,
                            pincode,
                            addressDetails
                        } = await getAddressDetails(
                            currentLocation.latitude,
                            currentLocation.longitude
                        );

                        currentAddress = fullAddress;
                        currentPincode = pincode;

                        document.getElementById('full_address').value = currentAddress;
                        document.getElementById('pincode').value = currentPincode;

                        // Update UI with address details
                        locationStatus.innerHTML = `Location captured: ${currentLocation.latitude.toFixed(6)}, ${currentLocation.longitude.toFixed(6)}`;

                        let addressHTML = `
                    <strong>Address:</strong> ${currentAddress}<br>
                    <strong>Pincode:</strong> ${currentPincode}<br>
                `;

                        // Add additional address components if available
                        if (addressDetails.village) addressHTML += `<strong>Village:</strong> ${addressDetails.village}<br>`;
                        if (addressDetails.city_district) addressHTML += `<strong>District:</strong> ${addressDetails.city_district}<br>`;
                        if (addressDetails.state) addressHTML += `<strong>State:</strong> ${addressDetails.state}<br>`;
                        if (addressDetails.country) addressHTML += `<strong>Country:</strong> ${addressDetails.country}<br>`;

                        addressDetails.innerHTML = addressHTML;
                        locationAlert.classList.remove('alert-info');
                        locationAlert.classList.add('alert-success');
                        submitBtn.disabled = false;
                    },
                    error => {
                        // Handle errors if geolocation fails
                        handleLocationError(error);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
            );
        } else {
            // Geolocation is not supported in the browser
            handleLocationError(new Error('Geolocation is not supported by this browser.'));
        }

        // Initialize camera
        function startCamera() {
            const constraints = {
                video: {
                    facingMode: 'environment', // Prefer rear camera
                    width: {
                        ideal: 1280
                    },
                    height: {
                        ideal: 720
                    }
                }
            };

            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    videoElement.srcObject = stream;
                })
                .catch(function(err) {
                    console.log('Camera error: ', err);
                    // Fallback to any available camera
                    navigator.mediaDevices.getUserMedia({
                            video: true
                        })
                        .then(function(stream) {
                            videoElement.srcObject = stream;
                        })
                        .catch(function(fallbackErr) {
                            console.log('Fallback camera error: ', fallbackErr);
                        });
                });
        }

        // Start the camera
        startCamera();

        // Capture image from video stream
        captureButton.addEventListener('click', function() {
            if (!currentLocation.latitude || !currentLocation.longitude) {
                alert('Please wait while we capture your location...');
                return;
            }

            const context = canvas.getContext('2d');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;

            // Draw the video frame
            context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

            // Add metadata overlay
            const captureTime = getCurrentTime();

            const locationText = `Lat: ${currentLocation.latitude.toFixed(6)}, Lon: ${currentLocation.longitude.toFixed(6)}`;
            const addressText = `Addr: ${currentAddress.substring(0, 50)}${currentAddress.length > 50 ? '...' : ''}`;
            const pincodeText = `Pincode: ${currentPincode}`;
            const timeText = `Time: ${captureTime}`;

            // Draw text on bottom of image with semi-transparent background
            context.fillStyle = 'rgba(0, 0, 0, 0.5)';
            context.fillRect(0, canvas.height - 100, canvas.width, 100);

            drawTextOnCanvas(canvas, locationText, 10, canvas.height - 80);
            drawTextOnCanvas(canvas, addressText, 10, canvas.height - 60);
            drawTextOnCanvas(canvas, pincodeText, 10, canvas.height - 40);
            drawTextOnCanvas(canvas, timeText, 10, canvas.height - 20);

            // Convert to data URL
            const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

            // Add to captured images array
            capturedImages.push(dataUrl);
            document.getElementById('capturedImages').value = JSON.stringify(capturedImages);

            // Show preview
            previewImage.src = dataUrl;
            previewLocation.textContent = locationText;
            previewAddress.textContent = `${currentAddress} (Pincode: ${currentPincode})`;
            previewTime.textContent = `Captured: ${captureTime}`;
            previewCard.style.display = 'block';

            // Scroll to preview
            previewCard.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        });
    });
</script>

<style>
    #videoElement {
        max-height: 400px;
        background-color: #000;
    }

    .camera-preview .card {
        max-width: 100%;
    }

    .camera-preview img {
        max-height: 300px;
        object-fit: contain;
    }

    #addressDetails {
        line-height: 1.6;
    }
</style>
@endsection