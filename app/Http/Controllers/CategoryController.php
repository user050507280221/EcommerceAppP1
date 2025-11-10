<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Show all categories with pagination
    public function index()
    {
        // Use paginate instead of all
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        Category::create($request->all());

        // Redirect to admin route after storing
        return redirect()->route('admin.categories.index')  // changed from categories.index to admin.categories.index
                         ->with('success', 'Category created successfully.');
    }

    // Delete a category
    public function destroy(Category $category)
    {
        // Note: Because we used `onDelete('set null')` in our migration,
        // deleting a category will not delete the products.
        // It will just set their `category_id` to null.
        $category->delete();

        // Redirect to admin route after deleting
        return redirect()->route('admin.categories.index')  // changed from categories.index to admin.categories.index
                         ->with('success', 'Category deleted successfully.');
    }
}
