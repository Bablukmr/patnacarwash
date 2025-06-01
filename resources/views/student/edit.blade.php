@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Client/Employee</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Edit Client</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Client/Employee Information</h3>
                        </div>

                        <form action="{{ route('admin.clientupdate', $client->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Full Name</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   value="{{ old('name', $client->name) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                   value="{{ old('email', $client->email) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password (Leave blank to keep current)</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" name="password"
                                                       id="password" placeholder="Enter new password">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary toggle-password"
                                                            type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="text" class="form-control" name="phone" id="phone"
                                                   value="{{ old('phone', $client->phone_number) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input type="text" class="form-control" name="address" id="address"
                                                   value="{{ old('address', $client->address) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" name="role" id="role" required>
                                                <option value="student" {{ $client->role == 'client' ? 'selected' : '' }}>Client</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" name="city" id="city"
                                                   value="{{ old('city', $client->city) }}" required>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <input type="text" class="form-control" name="state" id="state"
                                                   value="{{ old('state', $client->state) }}" required>
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="pincode">Pincode</label>
                                            <input type="text" class="form-control" name="pincode" id="pincode"
                                                   value="{{ old('pincode', $client->pincode) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('admin.clientlist') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle password visibility
        $('.toggle-password').click(function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Phone number validation
        $('#phone').inputmask('9999999999', { placeholder: '0123456789' });

        // Pincode validation
        $('#pincode').inputmask('999999', { placeholder: '123456' });
    });
</script>
@endpush
