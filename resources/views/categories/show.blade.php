<!DOCTYPE html>
<html>
<head>
    <title>Category Details</title> <!-- Page title shown in browser tab -->
    <!-- Bootstrap 5 CSS CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light"> <!-- Light gray background for the page -->
<div class="container mt-5"> <!-- Main container with top margin -->
    <div class="card shadow-sm"> <!-- Card container with slight shadow -->
        <div class="card-header bg-info text-white"> <!-- Card header with blue background and white text -->
            <h4 class="mb-0">Category Details</h4> <!-- Header title -->
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{ $category->id }}</p> <!-- Display category ID -->
            <p><strong>Name:</strong> {{ $category->name }}</p> <!-- Display category name -->
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a> <!-- Button to go back to category list -->
        </div>
    </div>
</div>
</body>
</html>
