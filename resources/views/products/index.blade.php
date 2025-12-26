<!DOCTYPE html>
<html>

<head>
    <title>Product List</title> <!-- Page title shown in browser tab -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token for AJAX requests -->

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa; /* Light gray page background */
        }

        .card {
            border-radius: 1rem; /* Rounded card corners */
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #6610f2); /* Gradient header */
            color: #fff; /* White text */
            border-top-left-radius: 1rem; /* Rounded top-left corner */
            border-top-right-radius: 1rem; /* Rounded top-right corner */
        }

        #productTable_wrapper {
            margin-top: 1rem; /* Spacing above DataTable */
        }

        table.dataTable tbody tr:hover {
            background-color: #e9f5ff; /* Highlight row on hover */
        }

        .dataTables_filter input {
            width: 300px; /* Wider search input */
        }

        .btn-action {
            margin-right: 3px; /* Spacing between action buttons */
        }
    </style>
</head>

<body>

    <div class="container mt-5"> <!-- Main container with top margin -->
        <div class="card shadow-sm"> <!-- Card container with shadow -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Product List</h4> <!-- Card title -->
                <a href="{{ route('products.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Add Product <!-- Button to add new product -->
                </a>
            </div>
            <div class="card-body">

                <!-- Category Filter -->
                <div class="mb-3 d-flex align-items-center gap-3 flex-wrap">
                    <label for="categoryFilter" class="fw-semibold mb-0">Filter by Category:</label>
                    <select id="categoryFilter" class="form-select w-auto">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option> <!-- Filter options -->
                        @endforeach
                    </select>
                </div>

                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center" id="productTable">
                        <thead class="table-primary">
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th> <!-- Show/Edit/Delete buttons -->
                            </tr>
                        </thead>
                        <tbody></tbody> <!-- DataTables will populate rows via AJAX -->
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(function () {
            // Initialize DataTable with server-side processing
            let table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('products.data') }}", // Fetch data via AJAX route
                    data: function (d) {
                        d.category_id = $('#categoryFilter').val(); // Send selected category for filtering
                    }
                },
                columns: [
                    { data: 'id', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'description' },
                    { data: 'price' },
                    { data: 'category' },
                    { data: 'actions', orderable: false, searchable: false } // Action buttons
                ],
                language: {
                    search: "_INPUT_", // Custom search input
                    searchPlaceholder: "Search products..." // Placeholder text
                }
            });

            // Reload table when category filter changes
            $('#categoryFilter').change(function () {
                table.ajax.reload();
            });
        });
    </script>

</body>

</html>
