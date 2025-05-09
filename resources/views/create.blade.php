
<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container">
    <h2 class="mb-4">Add Employee</h2>

    <form id="employeeForm" class="card p-4 shadow-sm bg-white" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Position</label>
            <input type="text" name="position" class="form-control" placeholder="Enter Position" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#employeeForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this); 

        $.ajax({
            url: '{{ route("employees.store") }}',
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.redirect_url) {
                    window.location.href = response.redirect_url;
                }
            },
            error: function(xhr) {
                alert('Something went wrong');
                console.error(xhr.responseText);
            }
        });
    });
</script>

</body>
</html>
