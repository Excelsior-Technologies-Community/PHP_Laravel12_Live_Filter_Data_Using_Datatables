# PHP_Laravel12_Live_Filter_Data_Using_Datatables

## Project Overview

This project is a **Laravel web application** that implements a complete **CRUD (Create, Read, Update, Delete)** system for **Categories** and **Products** with a **modern UI** and **live data filtering** using **Yajra DataTables**.

The main highlight of this project is the **Product Listing page**, where products are displayed using **server-side DataTables** and filtered dynamically by **Category dropdown without page refresh** using **AJAX**.

This project follows **Laravel MVC architecture**, clean coding standards, and is suitable for **college/company assignments and practical learning**.

---

## Project Objectives

- Implement full **CRUD operations** for Categories and Products
- Use **Laravel MVC structure** correctly
- Integrate **Yajra DataTables** with server-side processing
- Implement **live filtering using AJAX**
- Maintain **professional UI using Bootstrap 5**
- Demonstrate **real-world Laravel project structure**

---

## 🛠️ Technologies Used

| Technology | Purpose |
|----------|---------|
| Laravel | Backend Framework |
| PHP | Server-side Programming |
| MySQL | Database |
| Bootstrap 5 | UI & Styling |
| jQuery | DOM & AJAX |
| Yajra DataTables | Server-side DataTables |
| HTML / CSS | Frontend Markup |

---

## Project Structure

```
PHP_Laravel12_Live_Filter_Data_Using_Datatables/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CategoryController.php
│   │       └── ProductController.php
│   │
│   └── Models/
│       ├── Category.php
│       └── Product.php
│
├── resources/
│   └── views/
│       ├── categories/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       │
│       └── products/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php
│
├── routes/
│   └── web.php
│
├── .env
│
├── artisan
├── composer.json
├── composer.lock
└── README.md
```

---


## Step 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel PHP_Laravel12_Live_Filter_Data_Using_Datatables "12.*"
cd PHP_Laravel12_Live_Filter_Data_Using_Datatables
php artisan serve
```

---


## Step 2: Database Configuration

Edit .env file:

```
DB_DATABASE=live_filter_datatable
DB_USERNAME=root
DB_PASSWORD=
```

Create database using this command:

```bash
php artisan migrate
```

---


## Step 3: Create Models & Migrations & Controllers

**Category**

```
php artisan make:model Category -mcr
```

**Product**

```
php artisan make:model Product -mcr
```

---


## Step 4: Migrations

**categories table**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is executed when we run: php artisan migrate
     */
    public function up(): void
    {
        // Create 'categories' table
        Schema::create('categories', function (Blueprint $table) {

            // Auto-increment primary key (id)
            $table->id();

            // Category name column
            $table->string('name');

            // Adds created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * This method is executed when we run: php artisan migrate:rollback
     */
    public function down(): void
    {
        // Drop 'categories' table if it exists
        Schema::dropIfExists('categories');
    }
};
```

**products table**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method runs when we execute: php artisan migrate
     */
    public function up(): void
    {
        // Create 'products' table
        Schema::create('products', function (Blueprint $table) {

            // Auto-increment primary key
            $table->id();

            // Product name
            $table->string('name');

            // Product description (optional)
            $table->text('description')->nullable(); // Product description

            // Product price with 2 decimal points
            $table->decimal('price', 8, 2);

            // Foreign key reference to categories table
            // category_id → categories.id
            // cascadeOnDelete means: if category is deleted, its products are also deleted
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // Adds created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * This method runs when we execute: php artisan migrate:rollback
     */
    public function down(): void
    {
        // Drop 'products' table if it exists
        Schema::dropIfExists('products');
    }
};
```

Run migrations:

```
php artisan migrate
```

---


## Step 5: Model Relationships

**Category.php**

File: app/Models/Category.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Fields that can be mass assigned using Category::create()
    protected $fillable = ['name'];

    /**
     * Relationship Method
     * One Category can have multiple Products
     * This links categories.id with products.category_id
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
```


**Product.php**

File: app/Models/Product.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Fields that are allowed for mass assignment (create/update)
    protected $fillable = ['name', 'description', 'price', 'category_id'];

    /**
     * Relationship Method
     * Each Product belongs to one Category
     * This links products.category_id with categories.id
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
```

---


## Step 6: Install Yajra DataTables Package

This project uses Yajra DataTables for server-side pagination, searching, sorting, and live filtering.

Install Package

```bash
composer require yajra/laravel-datatables-oracle
```

---


## Step 7: Create Controllers Logic

**CategoryController**

File: app/Http/Controllers/CategoryController.php

```php
<?php

namespace App\Http\Controllers;

// Import Request class to handle form data
use Illuminate\Http\Request;

// Import Category model to interact with categories table
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     * URL: /categories
     * Method: GET
     */
    public function index()
    {
        // Fetch all categories from database
        $categories = Category::all();

        // Return categories index view with data
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     * URL: /categories/create
     * Method: GET
     */
    public function create()
    {
        // Load category create form
        return view('categories.create');
    }

    /**
     * Store a newly created category in database
     * URL: /categories
     * Method: POST
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Insert new category record into database
        Category::create([
            'name' => $request->name
        ]);

        // Redirect back to category list with success message
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category added successfully!');
    }

    /**
     * Display the specified category details
     * URL: /categories/{id}
     * Method: GET
     */
    public function show($id)
    {
        // Find category by ID or throw 404 error
        $category = Category::findOrFail($id);

        // Show category details page
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category
     * URL: /categories/{id}/edit
     * Method: GET
     */
    public function edit($id)
    {
        // Fetch category record for editing
        $category = Category::findOrFail($id);

        // Load edit form with category data
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in database
     * URL: /categories/{id}
     * Method: PUT / PATCH
     */
    public function update(Request $request, $id)
    {
        // Validate updated category data
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Find category by ID
        $category = Category::findOrFail($id);

        // Update category name
        $category->update([
            'name' => $request->name
        ]);

        // Redirect back with success message
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from database
     * URL: /categories/{id}
     * Method: DELETE
     */
    public function destroy($id)
    {
        // Find category record
        $category = Category::findOrFail($id);

        // Delete category from database
        $category->delete();

        // Redirect back with success message
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
```


**ProductController (with DataTables Live Filter)**

File: app/Http/Controllers/ProductController.php


```php
<?php

namespace App\Http\Controllers;

// Import Product model to interact with products table
use App\Models\Product;

// Import Category model for dropdown & relationship usage
use App\Models\Category;

// Import Request class to handle form inputs
use Illuminate\Http\Request;

// Import Yajra DataTables for server-side DataTables processing
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display product listing page
     * URL: /products
     * Method: GET
     */
    public function index()
    {
        // Fetch all categories for category filter dropdown
        $categories = Category::all();

        // Load products index view
        return view('products.index', compact('categories'));
    }

    /**
     * Show product creation form
     * URL: /products/create
     * Method: GET
     */
    public function create()
    {
        // Fetch categories for product category selection
        $categories = Category::all();

        // Load product create form
        return view('products.create', compact('categories'));
    }

    /**
     * Store newly created product in database
     * URL: /products
     * Method: POST
     */
    public function store(Request $request)
    {
        // Validate product form input data
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Insert product record into database
        Product::create($request->all());

        // Redirect back to product list with success message
        return redirect()
            ->route('products.index')
            ->with('success', 'Product added successfully!');
    }

    /**
     * Fetch products data for DataTables (AJAX request)
     * URL: /products-data
     * Method: GET
     */
   public function getData(Request $request)
{
    // Build base query and load category relationship
    $query = Product::with('category');

    // Return data in DataTables compatible JSON format
    return DataTables::of($query)

        /* ---------- CATEGORY FILTER & GLOBAL SEARCH ---------- */
        ->filter(function ($query) use ($request) {

            // Apply category dropdown filter if a category is selected
            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // Apply global search from DataTables search box
            if ($request->search['value']) {
                $search = $request->search['value'];

                // Search across multiple product fields and related category name
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")          // Search by product name
                      ->orWhere('description', 'like', "%{$search}%") // Search by description
                      ->orWhere('price', 'like', "%{$search}%")       // Search by price
                      ->orWhereHas('category', function ($c) use ($search) {
                          $c->where('name', 'like', "%{$search}%");   // Search by category name
                      });
                });
            }

        })

        // Add category column to DataTable response
        ->addColumn('category', function ($row) {
            return $row->category->name ?? '-';
        })

        // Add action buttons (Show, Edit, Delete) column
        ->addColumn('actions', function ($row) {
            return '
                <a href="'.route('products.show',$row->id).'" class="btn btn-sm btn-info me-1">Show</a>
                <a href="'.route('products.edit',$row->id).'" class="btn btn-sm btn-warning me-1">Edit</a>
                <form action="'.route('products.destroy',$row->id).'" method="POST" style="display:inline-block;">
                    '.csrf_field().method_field('DELETE').'
                    <button class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                </form>
            ';
        })

        // Allow HTML content inside the actions column
        ->rawColumns(['actions'])

        // Generate final JSON response for DataTables
        ->make(true);
}


    /**
     * Display single product details
     * URL: /products/{id}
     * Method: GET
     */
    public function show($id)
    {
        // Fetch product with its category
        $product = Product::with('category')->findOrFail($id);

        // Load product details page
        return view('products.show', compact('product'));
    }

    /**
     * Show product edit form
     * URL: /products/{id}/edit
     * Method: GET
     */
    public function edit($id)
    {
        // Fetch product record
        $product = Product::findOrFail($id);

        // Fetch all categories for dropdown
        $categories = Category::all();

        // Load edit form view
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update existing product
     * URL: /products/{id}
     * Method: PUT / PATCH
     */
    public function update(Request $request, $id)
    {
        // Fetch product by ID
        $product = Product::findOrFail($id);

        // Validate updated product data
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Update product record
        $product->update($request->all());

        // Redirect back with success message
        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product from database
     * URL: /products/{id}
     * Method: DELETE
     */
    public function destroy($id)
    {
        // Fetch product record
        $product = Product::findOrFail($id);

        // Delete product
        $product->delete();

        // Redirect back with success message
        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
```


---


## Step 8: Define Routes

File: routes/web.php


```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Category Routes (CRUD)
|--------------------------------------------------------------------------
*/

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

/*
|--------------------------------------------------------------------------
| Product Routes (CRUD)
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

/*
|--------------------------------------------------------------------------
| DataTables Live Filter Route
|--------------------------------------------------------------------------
*/
Route::get('/products-data', [ProductController::class, 'getData'])
    ->name('products.data');
```

---


## Step 9: Create Blade Views (UI)


#### 1) Category Views

**index.blade.php**

File: resources/views/categories/index.blade.php

Explaination: This page displays a list of all categories in a responsive table with options to show, edit, or delete each category. It also includes a button to add a new category and
 
shows success messages after actions.

```
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
```

**create.blade.php**

File: resources/views/categories/create.blade.php

Explaination: Provides a form to add a new category with validation for the category name. Includes Back and Submit buttons and displays validation errors if any.

```
<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title> <!-- Page title shown in browser tab -->
    <!-- Bootstrap 5 CSS CDN for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light"> <!-- Light background for the page -->
<div class="container mt-5"> <!-- Main container with top margin -->
    <div class="card shadow-sm"> <!-- Card container with slight shadow -->
        <div class="card-header bg-primary text-white"> <!-- Card header with blue background and white text -->
            <h4 class="mb-0">Add New Category</h4> <!-- Header title -->
        </div>
        <div class="card-body">
            <!-- Display validation errors if any exist -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error) <!-- Loop through each validation error -->
                        <li>{{ $error }}</li> <!-- Show individual error -->
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form to add a new category -->
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf <!-- CSRF token for security -->

                <div class="mb-3"> <!-- Form group with bottom margin -->
                    <label for="name" class="form-label">Category Name</label> <!-- Label for input -->
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required> <!-- Input field for category name -->
                </div>

                <div class="d-flex justify-content-between"> <!-- Flex container for buttons -->
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a> <!-- Back button -->
                    <button type="submit" class="btn btn-primary">Add Category</button> <!-- Submit button to save category -->
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
```

**edit.blade.php**

File: resources/views/categories/edit.blade.php

Explaination: Shows a form pre-filled with the category data to update an existing category. Includes Back and Update buttons and displays validation errors if the input is invalid.

```
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
```

**show.blade.php**

File: resources/views/categories/show.blade.php

Explaination: Displays details of a single category, including its ID and Name, with a Back button to return to the category list.

```
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
```


#### 2) Product Views

**index.blade.php**

File: resources/views/products/index.blade.php

Explaination: This page displays a list of all products in a server-side DataTable with columns for ID, Name, Description, Price, Category, and Actions. Users can filter products by
 
category using the dropdown without page reload, thanks to AJAX. Each row includes action buttons to show, edit, or delete a product. The table also supports searching, pagination, and 

hover highlights for a clean UI.

```
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
```

**create.blade.php**

File: resources/views/products/create.blade.php

Explaination: This page provides a form to add a new product, including fields for Name, Description, Price, and Category. It also includes validation error messages and Back / Submit
 
buttons for smooth navigation.

```
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
```


**edit.blade.php**

File: resources/views/products/edit.blade.php

Explaination: This page allows users to edit an existing product, pre-filling its name, description, price, and category. It includes validation messages and buttons to update the product 

or go back to the product list.

```
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
```


**show.blade.php**

File: resources/views/products/show.blade.php

Explaination: This page displays the details of a single product, including its name, description, price, and category. It also provides a Back button to return to the product listing

page.

```
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
```

---


## Step 10: Run Project

```bash
php artisan serve
```

**Open browser:**

```
http://127.0.0.1:8000/products
```

---

## Output

#### Filter by Category in Product Index Page

<img width="1919" height="1030" alt="Screenshot 2025-12-26 101901" src="https://github.com/user-attachments/assets/794676b9-a60e-465d-863e-bd6e057057ac" />

<img width="1919" height="1030" alt="Screenshot 2025-12-26 101912" src="https://github.com/user-attachments/assets/156e4d19-3dd9-487f-8b00-ed5e108b9b61" />

<img width="1919" height="1029" alt="Screenshot 2025-12-26 101923" src="https://github.com/user-attachments/assets/c6a1d4fb-b78d-4865-83fd-19a6bbd42958" />

---

Your PHP_Laravel12_Live_Filter_Data_Using_Datatables Project is Now Ready!
