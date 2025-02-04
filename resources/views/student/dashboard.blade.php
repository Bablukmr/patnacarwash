@extends('student.layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Car Wash Booking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Car Wash Booking</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Book Your Car Wash</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('student.booking') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <!-- Personal Information -->
                                <h5>Personal Information</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
                                    </div>
                                </div>

                                <!-- Car Details -->
                                <h5>Car Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="car_model">Car Model</label>
                                        <input type="text" class="form-control" id="car_model" name="car_model" placeholder="Enter car model" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="car_type">Car Type</label>
                                        <select class="form-control" id="car_type" name="car_type" required>
                                            <option value="sedan">Sedan</option>
                                            <option value="suv">SUV</option>
                                            <option value="truck">Truck</option>
                                            <option value="hatchback">Hatchback</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="license_plate">License Plate</label>
                                        <input type="text" class="form-control" id="license_plate" name="license_plate" placeholder="Enter license plate number" required>
                                    </div>
                                </div>

                                <!-- Multiple Image Upload with Preview -->
                                <h5>Car Images</h5>
                                <div class="form-group">
                                    <label for="car_images">Upload Car Images</label>
                                    <input type="file" class="form-control" id="car_images" name="car_images[]"  accept=".jpg, .jpeg, .png" multiple onchange="previewImages()" required>
                                </div>
                                <div class="form-group">
                                    <div id="image_preview" class="row"></div>
                                </div>

                                <!-- Service Details -->
                                <h5>Service Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="service_type">Select Service</label>
                                        <select class="form-control" id="service_type" name="service_type" required>
                                            <option value="basic_wash">Basic Wash</option>
                                            <option value="premium_wash">Premium Wash</option>
                                            <option value="deluxe_wash">Deluxe Wash</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="preferred_date">Preferred Date</label>
                                        <input type="date" class="form-control" id="preferred_date" name="preferred_date" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="preferred_time">Preferred Time</label>
                                        <input type="time" class="form-control" id="preferred_time" name="preferred_time" required>
                                    </div>
                                </div>

                                <!-- Location -->
                                <h5>Location Details</h5>
                                <div class="form-group">
                                    <label for="address">Service Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter address for car wash service" required></textarea>
                                </div>

                                <!-- Payment Information -->
                                <h5>Payment Information</h5>
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                        <option value="online_payment">Online Payment</option>
                                    </select>
                                </div>

                                <!-- Online Payment Options (shown if selected) -->
                                <div id="online-payment-options" style="display: none;">
                                    <h6>Choose Online Payment Method</h6>
                                    <div class="form-group">
                                        <label for="credit_card">Credit/Debit Card</label>
                                        <input type="text" class="form-control" id="credit_card" name="credit_card" placeholder="Enter card number">
                                    </div>
                                    <div class="form-group">
                                        <label for="upi">UPI ID</label>
                                        <input type="text" class="form-control" id="upi" name="upi" placeholder="Enter UPI ID">
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Book Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    // Preview multiple images
    function previewImages() {
        const preview = document.getElementById('image_preview');
        preview.innerHTML = ''; // Clear previous previews

        const files = document.getElementById('car_images').files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.classList.add('img-thumbnail');
                img.style.width = '150px';
                img.style.margin = '5px';
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(files[i]);
        }
    }

    // Show online payment options if selected
    document.getElementById('payment_method').addEventListener('change', function() {
        if (this.value === 'online_payment') {
            document.getElementById('online-payment-options').style.display = 'block';
        } else {
            document.getElementById('online-payment-options').style.display = 'none';
        }
    });
</script>

@endsection