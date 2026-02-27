<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class RankController extends Controller
{
    public function index()
    {
        $ranks = Option::where('option_group', 'rank')->get();
        return view('backend.reference.ranks', compact('ranks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'rank',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Rank created successfully.');
    }

    public function update(Request $request, Option $rank)
    {
        if ($rank->option_group !== 'rank') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $rank->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Rank updated successfully.');
    }

    public function destroy(Option $rank)
    {
        if ($rank->option_group !== 'rank') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $rank->delete();
        return redirect()->back()->with('success', 'Rank deleted successfully.');
    }
}
