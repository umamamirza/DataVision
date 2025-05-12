

<!DOCTYPE html>
<html>
<head>
    <title>Employee List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .table img {
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">Employees List</h2>
        <a href="{{ route('employees.create') }}" class="btn btn-success">Add Employee</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="employeeTable" class="table table-hover table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    var table = $('#employeeTable').DataTable({
        ajax: '{{ route("employees.data") }}',
        processing: true,
        serverSide: true,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: "text-center" },
            { data: 'photo', name: 'photo', orderable: false, searchable: false, className: "text-center" },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'position', name: 'position' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center" }
        ]
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name=\"csrf-token\"]').attr('content')
        }
    });

    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this employee?')) {
            $.ajax({
                url: '/employees/' + id,
                type: 'DELETE',
                success: function (res) {
                    if (res.success) {
                        table.ajax.reload();
                    }
                }
            });
        }
    });
});
</script>

</body>
</html>
