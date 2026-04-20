<!DOCTYPE html>
<html>

<head>
    <title>Product List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #6610f2);
            color: #fff;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        #productTable_wrapper {
            margin-top: 1rem;
        }

        table.dataTable tbody tr:hover {
            background-color: #e9f5ff;
        }

        .dataTables_filter input {
            width: 300px;
        }

        .btn-action {
            margin-right: 3px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Product List</h4>
                <a href="{{ route('products.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
            <div class="card-body">

                <!-- Filters Row -->
                <div class="row mb-3 g-3">
                    <!-- Category Filter -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-0">Category:</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold mb-0">Min Price:</label>
                        <input type="number" id="min_price" class="form-control" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold mb-0">Max Price:</label>
                        <input type="number" id="max_price" class="form-control" placeholder="0">
                    </div>

                    <!-- Sort By Date -->
                    <div class="col-md-2">
                        <label class="form-label fw-semibold mb-0">Sort By Date:</label>
                        <select id="sort_date" class="form-select">
                            <option value="">Default</option>
                            <option value="asc">Oldest</option>
                            <option value="desc">Newest</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="filterBtn" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="mb-3 text-end">
                    <a href="{{ route('products.export', 'csv') }}" class="btn btn-success">CSV</a>
                    <a href="{{ route('products.export', 'xlsx') }}" class="btn btn-success">Excel</a>
                    <a href="{{ route('products.export', 'pdf') }}" class="btn btn-danger">PDF</a>
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
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Value:</th>
                                <th colspan="4" id="totalValue">0</th>
                            </tr>
                        </tfoot>
                        <tbody></tbody>
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
            // Setup CSRF for AJAX
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            // Initialize DataTable
            let table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('products.index') }}",
                    data: function (d) {
                        d.category_id = $('#categoryFilter').val();
                        d.min_price = $('#min_price').val();
                        d.max_price = $('#max_price').val();
                        d.sort_date = $('#sort_date').val();
                    }
                },
                columns: [
                    { data: 'id', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'description' },
                    { data: 'price' },
                    { data: 'category' },
                    { data: 'created_at' },
                    { data: 'actions', orderable: false, searchable: false }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products..."
                },
                lengthMenu: [[3, 5, 10, 25, 50, -1], [3, 5, 10, 25, 50, "All"]], // <-- Add this line
                drawCallback: function (settings) {
                    // Calculate total price for visible rows
                    let total = this.api().column(3, { page: 'current' }).data().reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    $('#totalValue').html(total.toFixed(2));
                }
            });

            // Apply filters
            $('#filterBtn, #categoryFilter, #min_price, #max_price, #sort_date').on('change click', function () {
                table.ajax.reload();
            });
        });
    </script>

</body>

</html>