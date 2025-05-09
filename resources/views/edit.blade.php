
<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="py-5 bg-light">

<div class="container">
<div >
            <h4 class="mb-0">Edit Employee</h4>
        </div>
        <br>
    <div class="card shadow-sm rounded">
       
        <div class="card-body">

            <form id="editForm" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ $employee->name }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ $employee->email }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <input type="text" name="position" value="{{ $employee->position }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Photo</label><br>
                    @if($employee->photo)
                        <img src="{{ asset('storage/' . $employee->photo) }}" width="100" class="mb-2 rounded shadow-sm d-block">
                    @endif
                    <input type="file" name="photo" class="form-control">
                </div>

                <div >
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#editForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    formData.append('_method', 'PUT');

    $.ajax({
        url: "{{ route('employees.update', $employee->id) }}",
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(res) {
            if (res.success) {
                window.location.href = res.redirect_url;
            }
        }
    });
});
</script>

</body>
</html>
