<!DOCTYPE html>
<html>

<head>
    <title>Category List</title> <!-- Page title shown in browser tab -->
    
    <!-- Bootstrap 5 CSS CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background for the page */
        }

        .card {
            border-radius: 1rem; /* Rounded corners for the card container */
        }

        .card-header {
            background: linear-gradient(90deg, #6610f2, #007bff); /* Gradient header */
            color: #fff; /* White text for header */
            font-weight: 500; /* Medium font weight */
            border-top-left-radius: 1rem; /* Rounded top-left corner */
            border-top-right-radius: 1rem; /* Rounded top-right corner */
        }

        .table-hover tbody tr:hover {
            background-color: #e9f5ff; /* Light blue highlight on row hover */
        }
    </style>
</head>

<body>
    <div class="container mt-5"> <!-- Main container with top margin -->
        <div class="card shadow-sm"> <!-- Card with slight shadow -->
            
            <!-- Card header with title and Add button -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Category List</h4> <!-- Card title -->
                <a href="{{ route('categories.create') }}" class="btn btn-light text-primary fw-bold">Add Category</a> <!-- Button to add new category -->
            </div>
            
            <div class="card-body">
                
                <!-- Show success message if session contains 'success' -->
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive"> <!-- Responsive table wrapper -->
                    <table class="table table-bordered table-striped table-hover text-center"> <!-- Styled table -->
                        <thead class="table-primary"> <!-- Table header with primary color -->
                            <tr>
                                <th>ID</th> <!-- Category ID column -->
                                <th>Category Name</th> <!-- Category Name column -->
                                <th>Actions</th> <!-- Actions column: Show/Edit/Delete -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop through all categories and display rows -->
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td> <!-- Display category ID -->
                                <td>{{ $category->name }}</td> <!-- Display category name -->
                                <td>
                                    <!-- Show category details -->
                                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-info">Show</a>
                                    
                                    <!-- Edit category -->
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    
                                    <!-- Delete category -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                                        @csrf <!-- CSRF token for security -->
                                        @method('DELETE') <!-- Use DELETE method for RESTful route -->
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
