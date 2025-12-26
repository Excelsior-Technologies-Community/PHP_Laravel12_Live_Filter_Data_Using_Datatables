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
