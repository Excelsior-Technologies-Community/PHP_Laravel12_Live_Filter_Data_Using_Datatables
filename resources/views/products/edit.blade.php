<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title> <!-- Page title shown in browser tab -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token for form security -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Light gray background for page */
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

        /* Gradient header with white text */
        .form-label {
            font-weight: 500;
        }

        /* Slightly bold labels */
        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }

        /* Gray back button */
        .btn-back:hover {
            background-color: #5a6268;
        }

        /* Darker gray on hover */
    </style>
</head>

<body>
    <div class="container mt-5"> <!-- Main container with top margin -->
        <div class="card shadow-sm"> <!-- Card with shadow effect -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Product</h4> <!-- Card header with icon -->
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf <!-- CSRF token -->
                    @method('PUT') <!-- HTTP method override for update -->

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name) }}"> <!-- Pre-fill current product name -->
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Show validation error -->
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea> <!-- Pre-fill description -->
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Show validation error -->
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" step="0.01"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', $product->price) }}"> <!-- Pre-fill price -->
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Show validation error -->
                        @enderror
                    </div>

                    <!-- Category Dropdown -->
                    <div class="mb-4">
                        <label for="category" class="form-label">Category</label>
                        <select name="category_id" id="category" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }} <!-- Pre-select current category -->
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Show validation error -->
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-back"><i class="bi bi-arrow-left-circle me-1"></i> Back</a> <!-- Back button -->
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Update Product</button> <!-- Submit button -->
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
</body>

</html>