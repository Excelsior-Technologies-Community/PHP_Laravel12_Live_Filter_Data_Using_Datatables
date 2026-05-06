<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        return view('products.index', compact('categories'));
    }

    public function getData(Request $request)
    {
        $query = Product::with('category');

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->filled('category_id')) {
                    $query->where('category_id', $request->category_id);
                }

                if ($request->filled('min_price')) {
                    $query->where('price', '>=', $request->min_price);
                }

                if ($request->filled('max_price')) {
                    $query->where('price', '<=', $request->max_price);
                }

                if ($request->has('search') && $request->search['value']) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('price', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($c) use ($search) {
                                $c->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->addColumn('category', function ($row) {
                return $row->category->name ?? 'N/A';
            })
            ->addColumn('actions', function ($row) {
                return '
                    <div class="d-flex justify-content-center">
                        <a href="' . route('products.show', $row->id) . '" class="btn btn-sm btn-info me-1"><i class="bi bi-eye"></i></a>
                        <a href="' . route('products.edit', $row->id) . '" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>
                        <form id="delete-form-' . $row->id . '" action="' . route('products.destroy', $row->id) . '" method="POST" style="display:none;">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function export(Request $request, $type)
    {
        $query = Product::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->get();

        if ($type == 'csv' || $type == 'xlsx') {
            return Excel::download(new ProductExport($products), 'products.' . $type);
        } elseif ($type == 'pdf') {
            $pdf = Pdf::loadView('products.pdf', compact('products'));
            return $pdf->download('products.pdf');
        }

        return redirect()->back();
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}