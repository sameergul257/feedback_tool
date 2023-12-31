<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::orderBy('id', 'desc')->paginate(10);
        return view('feedback.index', compact('feedbacks'));
    }

    public function create(Request $request)
    {
        $feedback = null;
        if (isset($request->id)) {
            $feedback = Feedback::findOrFail($request->id);
        }
        $feedback_categories = FeedbackCategory::get(['id', 'name']);
        return view('feedback.create', compact('feedback_categories', 'feedback'));
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
                if ($feedback->submitted_by != auth()->user()->id) {
                    return redirect()->route('feedback.index')->with('error', 'Permission denied, Cannot edit this feedback');
                }
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
        $validator = Validator::make(
            $request->all(),
            [
                'content' => 'required',
            ],
            [
                'content.required' => 'Comment field is required.',
            ]
        );

        if ($validator->fails()) {
            return response(['status' => false, 'message' => 'Comment field is required.']);
        }

        try {
            $commenting_check = Feedback::findOrFail($request->feedback_id);
            if ($commenting_check->is_comment_enabled == '0') {
                return response(['status' => false, 'message' => 'Comments disabled for this feedback by Admin']);
            }

            Comment::create([
                'content' => $request->input('content'),
                'user_id' => auth()->user()->id,
                'feedback_id' => $request->feedback_id,
            ]);
            return response(['status' => true, 'message' => 'Comment added successfully']);
        } catch (\Throwable $th) {
            //throw $th;
            return response(['status' => false, 'message' => 'Error adding comment']);
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

    public function changecommentingstatus(Request $request)
    {
        try {
            $id = $request->id;
            $is_comment_enabled = $request->is_comment_enabled;

            $feedback = Feedback::findOrFail($id);
            $feedback->is_comment_enabled = $is_comment_enabled;
            $feedback->save();

            return response(['status' => true, 'message' => 'Commenting status updated successfully']);
        } catch (\Throwable $th) {
            //throw $th;
            return response(['status' => false, 'message' => 'Error changing commenting status']);
        }
    }
}
