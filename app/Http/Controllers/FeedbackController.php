<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackVote;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::paginate(10);
        // dd($feedbacks);
        return view('feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        $feedback_categories = FeedbackCategory::get(['id', 'name']);
        return view('feedback.create', compact('feedback_categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'feedback_category_id' => 'required|integer',
        ]);

        try {
            if (isset($request->id)) {
                $feedback = Feedback::findOrFail($request->id);
            } else {
                $feedback = new Feedback();
            }
            $feedback->fill($request->all());
            $feedback->submitted_by = auth()->user()->id;
            $feedback->save();
            return redirect()->route('feedback.index')->with('status', 'Feedback added successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('feedback.index')->with('error', 'Error performing action');
        }
    }

    public function view($id)
    {
        $feedback = Feedback::findOrFail($id);
        $voted = FeedbackVote::where('feedback_id', $id)->where('user_id', auth()->user()->id)->value('id');
        return view('feedback.view', compact('feedback', 'voted'));
    }

    public function vote(Request $request)
    {
        try {
            $feedback = Feedback::findOrFail($request->id);
            if (!$feedback->hasUserVoted(auth()->user())) {
                FeedbackVote::create([
                    'user_id' => auth()->user()->id,
                    'feedback_id' => $feedback->id,
                ]);
                return redirect()->route('feedback.view', ['id' => $request->id])->with('status', 'Voted successfully');
            } else {
                $feedback = FeedbackVote::where('feedback_id', $request->id)->where('user_id', auth()->user()->id)->delete();
                return redirect()->route('feedback.view', ['id' => $request->id])->with('status', 'Vote removed successfully');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('feedback.view', ['id' => $request->id])->with('error', 'Error performing action');
        }
    }

    public function add_comment(Request $request)
    {
        $request->validate(
            [
                'content' => 'required',
            ],
            [
                'content.required' => 'Comment field is required.',
            ]
        );

        try {
            Comment::create([
                'content' => $request->input('content'),
                'user_id' => auth()->user()->id,
                'feedback_id' => $request->feedback_id,
            ]);
            return redirect()->back()->with('status', 'Comment added successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Error adding comment');
        }
    }

    public function get_comments_list(Request $request)
    {
        $feedback = Feedback::findOrFail($request->feedback_id);
        $comments = $feedback->comments;
        return view('components.comment-list-template', compact('comments'))->render();
    }

    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return redirect()->back()->with('error', 'Feedback not found');
        }
        try {
            $feedback->votes()->delete();
            $feedback->comments()->delete();
            $feedback->delete();
            return redirect()->back()->with('status', 'Feedback deleted successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Error deleting feedback ' . $th->getMessage());
        }
    }
}
