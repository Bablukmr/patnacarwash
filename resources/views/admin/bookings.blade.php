@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Booking Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Bookings</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">List of Bookings</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Assign Work</th>
                                        <th>Status</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Car Model</th>
                                        <th>Service Type</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Address</th>
                                        <!-- @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin')
                                        <th>User ID</th>
                                        @endif -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $booking->name }}</td>
                                        <td>
                                            @if($booking->workAssignment)
                                            Assigned to: {{ $booking->workAssignment->employee->name }}
                                            <br>
                                            <a href="{{ route('admin.assign-work', $booking) }}" class="btn btn-sm btn-warning">
                                                Change Assign
                                            </a>
                                            @else
                                            <a href="{{ route('admin.assign-work', $booking) }}" class="btn btn-sm btn-success">
                                                Assign Work
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($booking->workAssignment)
                                            <a href="{{ route('admin.booking-details', $booking) }}" 
                                               class="btn btn-sm btn-info">
                                                View Status
                                            </a>
                                            @else
                                            <span class="badge badge-secondary">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $booking->email }}</td>
                                        <td>{{ $booking->phone }}</td>
                                        <td>{{ $booking->car_model }}</td>
                                        <td>{{ $booking->service_type }}</td>
                                        <td>{{ $booking->preferred_date }}</td>
                                        <td>{{ $booking->preferred_time }}</td>
                                        <td>{{ $booking->address }}</td>
                                        <!-- @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'admin')
                                        <td>{{ $booking->user_id }}</td>
                                        @endif -->
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('extraCss')
<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endsection

@section('extraJs')
<script src="plugins/jquery/jquery.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection