<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title> <!-- Page title shown in browser tab -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token for form security -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .form-label {
            font-weight: 500; /* Slightly bolder labels */
        }

        .btn-back {
            background-color: #6c757d; /* Gray back button */
            color: #fff;
        }

        .btn-back:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
    </style>
</head>

<body>

    <div class="container mt-5"> <!-- Main container with top margin -->
        <div class="card shadow-sm"> <!-- Card with shadow -->
            <div class="card-header">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Product</h4> <!-- Card header with icon -->
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf <!-- CSRF token -->

                    <!-- Product Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Enter product name" value="{{ old('name') }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Display error message -->
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description"
                            class="form-control @error('description') is-invalid @enderror" rows="4"
                            placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Display error message -->
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" step="0.01"
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="Enter price" value="{{ old('price') }}">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Display error message -->
                        @enderror
                    </div>

                    <!-- Category Dropdown -->
                    <div class="mb-4">
                        <label for="category" class="form-label">Category</label>
                        <select name="category_id" id="category"
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option> <!-- Loop through categories -->
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div> <!-- Display error message -->
                        @enderror
                    </div>

                    <!-- Submit & Back Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-back">
                            <i class="bi bi-arrow-left-circle me-1"></i> Back <!-- Back button -->
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Add Product <!-- Submit button -->
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
