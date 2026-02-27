<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quote;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::orderBy('id', 'desc')->get();
        return view('backend.blog.quotes', compact('quotes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote' => 'required|string|max:500',
            'quote_by' => 'required|string|max:255'
        ]);

        Quote::create($validated);
        return redirect()->back()->with('success', 'Quote created successfully.');
    }

    public function update(Request $request, Quote $quote)
    {
        $validated = $request->validate([
            'quote' => 'required|string|max:500',
            'quote_by' => 'required|string|max:255'
        ]);

        $quote->update($validated);
        return redirect()->back()->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->back()->with('success', 'Quote deleted successfully.');
    }
}
