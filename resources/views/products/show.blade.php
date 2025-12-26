<!DOCTYPE html>
<html>

<head>
    <title>View Product</title> <!-- Page title shown in browser tab -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token for security -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Light gray page background */
        .card {
            border-radius: 1rem;
        }

        /* Rounded card corners */
        .card-header {
            background: linear-gradient(90deg, #007bff, #6610f2);
            color: #fff;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
        }

        /* Gradient header */
        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }

        /* Gray back button */
        .btn-back:hover {
            background-color: #5a6268;
        }

        /* Darker gray on hover */
        .label {
            font-weight: 500;
        }

        /* Bold labels for field names */
    </style>
</head>

<body>
    <div class="container mt-5"> <!-- Main container with top margin -->
        <div class="card shadow-sm"> <!-- Card with shadow effect -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-eye-fill me-2"></i>Product Details</h4> <!-- Header with icon -->
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="label">Name:</span>
                    <p>{{ $product->name }}</p> <!-- Display product name -->
                </div>
                <div class="mb-3">
                    <span class="label">Description:</span>
                    <p>{{ $product->description ?? '-' }}</p> <!-- Display description or dash if empty -->
                </div>
                <div class="mb-3">
                    <span class="label">Price:</span>
                    <p>{{ $product->price }}</p> <!-- Display product price -->
                </div>
                <div class="mb-3">
                    <span class="label">Category:</span>
                    <p>{{ $product->category->name }}</p> <!-- Display related category name -->
                </div>

                <a href="{{ route('products.index') }}" class="btn btn-back"><i class="bi bi-arrow-left-circle me-1"></i> Back</a> <!-- Back button -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
</body>

</html>