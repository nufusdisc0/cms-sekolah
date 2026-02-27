<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\Question;

class AnswerController extends Controller
{
    public function index()
    {
        // Get answers with their associated questions
        $answers = Answer::join('questions', 'answers.question_id', '=', 'questions.id')
            ->select('answers.*', 'questions.question')
            ->latest('answers.created_at')
            ->get();
        $questions = Question::latest()->get();
        return view('backend.plugins.answers.index', compact('answers', 'questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'answer' => 'required',
            'question_id' => 'required|exists:questions,id'
        ]);

        Answer::create($validated);

        return redirect()->back()->with('success', 'Answer created successfully.');
    }

    public function update(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'answer' => 'required',
            'question_id' => 'required|exists:questions,id'
        ]);

        $answer->update($validated);

        return redirect()->back()->with('success', 'Answer updated successfully.');
    }

    public function destroy(Answer $answer)
    {
        $answer->delete();

        return redirect()->back()->with('success', 'Answer deleted successfully.');
    }
}
