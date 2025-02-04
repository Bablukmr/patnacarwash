@extends('employee.layout')

@section('content')
<div class="content-wrapper p-3">
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Assigned Bookings</h3>
                </div>

                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Car Model</th>
                                <th>Service Type</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignments as $assignment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $assignment->booking->id }}</td>
                                <td>{{ $assignment->booking->name }}</td>
                                <td>{{ $assignment->booking->car_model }}</td>
                                <td>{{ $assignment->booking->service_type }}</td>
                                <td>{{ $assignment->booking->preferred_date }}</td>
                                <td>
                                    <span class="badge badge-{{ $assignment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('teacher.update-work', $assignment) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Update
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> <!-- /.card-body -->
            </div> <!-- /.card -->
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
<!-- DataTables & Plugins -->
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
        var table = $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "pageLength": 10,
        });

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection
