<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    // Show the "create new product" form
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Store the new product in the database / Save the new product
    public function store(Request $request)
    {
        // Validate incoming data, including category
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id', // <-- Added for Handmade Crafts
        ]);

        Product::create($request->all());

        // Redirect to admin route after storing
        return redirect()->route('admin.products.index')
                         ->with('success', 'Craft added successfully.');
    }

    // Show a single product
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Show the "edit product" form
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Save the changes to an existing product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id', // <-- Added for Handmade Crafts
        ]);

        $product->update($request->all());

        // Redirect to admin route after updating
        return redirect()->route('admin.products.index')
                         ->with('success', 'Craft updated successfully.');
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $product->delete();

        // Redirect to admin route after deleting
        return redirect()->route('admin.products.index')
                         ->with('success', 'Craft deleted successfully.');
    }

    // Shop page
    public function shop()
    {
        // We eager load 'category' to show it on the shop page
        $products = Product::with('category')->latest()->paginate(12);

        return view('shop.index', compact('products'));
    }
}
