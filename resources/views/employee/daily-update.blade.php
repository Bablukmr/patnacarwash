@extends('employee.layout')
@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daily Update - {{ date('Y-m-d') }}</h3>
                </div>
                <form id="updateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        
                        <!-- Camera Capture -->
                        <div class="form-group">
                            <label>Capture Work Photos</label>
                            <div class="camera-preview mb-2"></div>
                            <input type="file" 
                                   name="images[]" 
                                   multiple 
                                   accept="image/*" 
                                   capture="environment"
                                   class="form-control-file camera-input">
                        </div>

                        <!-- Location Data -->
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <div class="alert alert-info location-info">
                            <i class="fas fa-map-marker-alt"></i> 
                            <span id="locationText">Acquiring location...</span>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Work Status</label>
                            <select name="status" class="form-control" required>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label>Work Notes</label>
                            <textarea name="notes" class="form-control" rows="4"></textarea>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get Geolocation
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
            document.getElementById('locationText').textContent = 
                `Location: ${position.coords.latitude}, ${position.coords.longitude}`;
        }, error => {
            console.error('Error getting location:', error);
            document.getElementById('locationText').textContent = 
                'Unable to get location';
        });
    }

    // Camera Preview
    const cameraInput = document.querySelector('.camera-input');
    const previewDiv = document.querySelector('.camera-preview');
    
    cameraInput.addEventListener('change', function(e) {
        previewDiv.innerHTML = '';
        Array.from(e.target.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.maxWidth = '200px';
            img.classList.add('img-thumbnail', 'mr-2', 'mb-2');
            previewDiv.appendChild(img);
        });
    });

    // Form Submission
    document.getElementById('updateForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch("{{ route('teacher.daily-update.store', $assignment) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                window.location.href = "{{ route('teacher.daily-updates') }}";
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});
</script>
@endsection
@endsection