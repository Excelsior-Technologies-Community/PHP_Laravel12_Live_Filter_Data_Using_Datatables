<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 1rem; border: none; }
        .card-header { 
            background: linear-gradient(90deg, #007bff, #6610f2); 
            color: #fff; 
            border-top-left-radius: 1rem; 
            border-top-right-radius: 1rem; 
        }
        .dataTables_filter input { width: 300px !important; border-radius: 5px; border: 1px solid #ddd; }
        table.dataTable thead th { background-color: #f1f4f9; }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center p-3">
                <h4 class="mb-0">Product Inventory</h4>
                <a href="{{ route('products.create') }}" class="btn btn-light text-primary fw-bold">
                    <i class="bi bi-plus-circle"></i> Add New Product
                </a>
            </div>
            <div class="card-body p-4">

                <div class="row mb-4 g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Category Filter</label>
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Min Price</label>
                        <input type="number" id="min_price" class="form-control" placeholder="Min">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Max Price</label>
                        <input type="number" id="max_price" class="form-control" placeholder="Max">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Sort Order</label>
                        <select id="sort_date" class="form-select">
                            <option value="">Default</option>
                            <option value="asc">Oldest First</option>
                            <option value="desc">Newest First</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="filterBtn" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Apply Filters</button>
                    </div>
                </div>

                <div class="mb-3 d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-success btn-export" data-type="csv"><i class="bi bi-filetype-csv"></i> CSV</button>
                    <button class="btn btn-outline-success btn-export" data-type="xlsx"><i class="bi bi-file-earmark-excel"></i> Excel</button>
                    <button class="btn btn-outline-danger btn-export" data-type="pdf"><i class="bi bi-file-pdf"></i> PDF</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle text-center" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Created Date</th>
                                <th width="150px">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot class="bg-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end">Page Total:</td>
                                <td colspan="4" id="totalValue" class="text-start text-primary">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            let table = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 0, 
                ajax: {
                    url: "{{ route('products.data') }}",
                    data: function (d) {
                        d.category_id = $('#categoryFilter').val();
                        d.min_price = $('#min_price').val();
                        d.max_price = $('#max_price').val();
                        d.sort_date = $('#sort_date').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'price', name: 'price' },
                    { data: 'category', name: 'category', searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Instant search..."
                },
                drawCallback: function (settings) {
                    let api = this.api();
                    let total = api.column(3, { page: 'current' }).data().reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    $('#totalValue').html('$' + total.toLocaleString(undefined, {minimumFractionDigits: 2}));
                }
            });

            $('#filterBtn, #categoryFilter, #sort_date').on('change click', function () {
                table.ajax.reload();
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this product!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(`#delete-form-${id}`).submit();
                    }
                });
            });

            $('.btn-export').on('click', function (e) {
                e.preventDefault();
                let type = $(this).data('type');
                let url = "{{ route('products.export', ':type') }}".replace(':type', type);
                let params = $.param({
                    category_id: $('#categoryFilter').val(),
                    min_price: $('#min_price').val(),
                    max_price: $('#max_price').val(),
                    sort_date: $('#sort_date').val()
                });
                window.location.href = url + '?' + params;
            });
        });
    </script>
</body>
</html>