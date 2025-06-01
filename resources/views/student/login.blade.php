<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Client Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admincss/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('admincss/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admincss/dist/css/adminlte.min.css') }}">

    <style>
        /* Password toggle styling */
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            color: #6c757d;
        }
        .password-input-group {
            position: relative;
        }

        /* Mobile responsiveness */
        @media (max-width: 576px) {
            .login-box {
                width: 95%;
                margin-top: 1rem;
            }
            .card-body {
                padding: 1.25rem;
            }
            .btn-block {
                padding: 0.5rem;
            }
            .login-box-msg {
                margin-bottom: 1.5rem;
            }
        }

        /* General improvements */
        .login-page {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .text-danger {
            display: block;
            margin-top: -0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1">Client<b>Login</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                @include('message')

                <form action="{{ route('student.authenticate') }}" method="post">
                    @csrf

                    <!-- Email Field -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <!-- Password Field with Toggle -->
                    <div class="input-group mb-3 password-input-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                    @error('password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>

                <p class="mb-1 mt-3">
                    <a >Forgot your password?</a>
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('admincss/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admincss/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admincss/dist/js/adminlte.min.js') }}"></script>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Auto-focus email field if empty
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.querySelector('input[name="email"]');
            if (emailField && emailField.value === '') {
                emailField.focus();
            }
        });
    </script>
</body>

</html>
