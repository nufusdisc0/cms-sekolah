<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('category_type', 'post')->get();
        return view('backend.blog.post_categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:500'
        ]);

        Category::create([
            'category_type' => 'post',
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'] ?? null,
            'category_slug' => Str::slug($validated['category_name'])
        ]);

        return redirect()->back()->with('success', 'Post Category created successfully.');
    }

    public function update(Request $request, Category $post_category)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:500'
        ]);

        $post_category->update([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'] ?? null,
            'category_slug' => Str::slug($validated['category_name'])
        ]);

        return redirect()->back()->with('success', 'Post Category updated successfully.');
    }

    public function destroy(Category $post_category)
    {
        $post_category->delete();
        return redirect()->back()->with('success', 'Post Category deleted successfully.');
    }
}
