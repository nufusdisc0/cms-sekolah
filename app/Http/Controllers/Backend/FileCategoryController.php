<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class FileCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('category_type', 'file')->orderBy('created_at', 'desc')->get();
        return view('backend.media.file_categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:1000'
        ]);

        Category::create([
            'category_type' => 'file',
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'] ?? null,
            'category_slug' => Str::slug($validated['category_name'])
        ]);

        return redirect()->back()->with('success', 'File Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('category_type', 'file')->findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string|max:1000'
        ]);

        $category->update([
            'category_name' => $validated['category_name'],
            'category_description' => $validated['category_description'] ?? null,
            'category_slug' => Str::slug($validated['category_name'])
        ]);

        return redirect()->back()->with('success', 'File Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::where('category_type', 'file')->findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'File Category deleted successfully.');
    }
}
