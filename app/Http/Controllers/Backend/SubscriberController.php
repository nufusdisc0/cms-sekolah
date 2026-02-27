<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::orderBy('id', 'desc')->get();
        return view('backend.blog.subscribers', compact('subscribers'));
    }

    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
}
