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
