<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories, newest first
        $categories = Category::withCount('products')->latest()->get();

        // Show categories in your Blade view
        return view('categories.index', compact('categories'));
    }

    public function destroy(Category $category)
    {
        // Note: Because we used `onDelete('set null')` in our migration,
        // deleting a category will not delete the products.
        // It will just set their `category_id` to null.
        $category->delete();

        return redirect()->route('categories.index')
                        ->with('success', 'Category deleted successfully.');
    }

}
