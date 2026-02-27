<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = File::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::where('category_type', 'file')->orderBy('category_name')->get();
        return view('backend.media.files', compact('files', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_title' => 'required|string|max:255',
            'file_description' => 'nullable|string|max:1000',
            'file_category_id' => 'required|exists:categories,id',
            'file_visibility' => 'required|in:public,private',
            'file' => 'required|file|max:20480' // max 20MB
        ]);

        $uploadedFile = $request->file('file');
        $fileName = $uploadedFile->getClientOriginalName();
        $fileExt = $uploadedFile->getClientOriginalExtension();
        $fileSize = $uploadedFile->getSize(); // in bytes

        // Convert to KB
        $fileSizeKb = round($fileSize / 1024, 2);

        $filePath = $uploadedFile->store('files', 'public');

        File::create([
            'file_title' => $validated['file_title'],
            'file_description' => $validated['file_description'] ?? null,
            'file_name' => $fileName,
            'file_type' => $uploadedFile->getMimeType(),
            'file_category_id' => $validated['file_category_id'],
            'file_path' => $filePath,
            'file_ext' => $fileExt,
            'file_size' => $fileSizeKb,
            'file_visibility' => $validated['file_visibility'],
            'file_counter' => 0
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();
        return redirect()->back()->with('success', 'File deleted successfully.');
    }
}
