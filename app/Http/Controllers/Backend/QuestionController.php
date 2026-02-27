<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::latest()->get();
        return view('backend.plugins.questions.index', compact('questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required',
            'is_active' => 'required|in:true,false'
        ]);

        Question::create($validated);

        return redirect()->back()->with('success', 'Question created successfully.');
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question' => 'required',
            'is_active' => 'required|in:true,false'
        ]);

        $question->update($validated);

        return redirect()->back()->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted successfully.');
    }
}
