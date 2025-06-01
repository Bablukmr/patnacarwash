<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
        }

        .show-password {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <script>
                            localStorage.setItem('savedEmail', "{{ old('email') }}");
                            localStorage.setItem('savedPassword', "{{ old('password') }}");
                        </script>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title text-center">Customer Registration</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('registerss') }}" method="POST">

                            @csrf

                            <div class="form-group mb-3">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                                    <button type="button" class="btn btn-outline-secondary show-password" id="toggle-password">
                                        <i class="bi bi-eye-slash" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Enter your phone number" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" id="address" placeholder="Enter your address" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="Enter your city" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="pincode">Pincode</label>
                                <input type="text" class="form-control" name="pincode" id="pincode" placeholder="Enter your pincode" required>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Register Now</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <p>Already have an account? <a href="student/login" id="login-link">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            passwordIcon.classList.toggle('bi-eye');
            passwordIcon.classList.toggle('bi-eye-slash');
        });

        // Auto-fill login credentials on the login page
        document.getElementById('login-link').addEventListener('click', function (e) {
            e.preventDefault();
            const savedEmail = localStorage.getItem('savedEmail');
            const savedPassword = localStorage.getItem('savedPassword');
            if (savedEmail && savedPassword) {
                window.location.href = "student/login?email=" + encodeURIComponent(savedEmail) + "&password=" + encodeURIComponent(savedPassword);
            } else {
                window.location.href = "student/login";
            }
        });
    </script>

</body>

</html>
