<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title> <!-- Page title shown in browser tab -->
    <!-- Bootstrap 5 CSS CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light"> <!-- Light gray background for the page -->
<div class="container mt-5"> <!-- Main container with top margin -->
    <div class="card shadow-sm"> <!-- Card container with slight shadow -->
        <div class="card-header bg-warning text-white"> <!-- Card header with yellow background and white text -->
            <h4 class="mb-0">Edit Category</h4> <!-- Header title -->
        </div>
        <div class="card-body">
            <!-- Display validation errors if any exist -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <!-- Loop through each validation error -->
                        <li>{{ $error }}</li> <!-- Display individual error -->
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form to edit an existing category -->
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf <!-- CSRF token for security -->
                @method('PUT') <!-- Use PUT method for RESTful update -->

                <div class="mb-3"> <!-- Form group with bottom margin -->
                    <label for="name" class="form-label">Category Name</label> <!-- Label for input -->
                    <input type="text" name="name" id="name" class="form-control" value="{{ $category->name }}" required> <!-- Input pre-filled with category name -->
                </div>

                <div class="d-flex justify-content-between"> <!-- Flex container for buttons -->
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a> <!-- Back button to category list -->
                    <button type="submit" class="btn btn-warning">Update Category</button> <!-- Submit button to update category -->
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
