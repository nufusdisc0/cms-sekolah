<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;

class SpecialNeedController extends Controller
{
    public function index()
    {
        $special_needs = Option::where('option_group', 'special_needs')->get();
        return view('backend.reference.special_needs', compact('special_needs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        Option::create([
            'option_group' => 'special_needs',
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Special Need created successfully.');
    }

    public function update(Request $request, Option $special_need)
    {
        if ($special_need->option_group !== 'special_needs') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $validated = $request->validate([
            'option_name' => 'required|string|max:255'
        ]);

        $special_need->update([
            'option_name' => $validated['option_name']
        ]);

        return redirect()->back()->with('success', 'Special Need updated successfully.');
    }

    public function destroy(Option $special_need)
    {
        if ($special_need->option_group !== 'special_needs') {
            return redirect()->back()->with('error', 'Invalid option group.');
        }

        $special_need->delete();
        return redirect()->back()->with('success', 'Special Need deleted successfully.');
    }
}
