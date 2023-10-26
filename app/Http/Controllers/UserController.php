<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereHas('role', function ($q) {
            return $q->where('name', '<>', 'Admin');
        })
            ->with('role')
            ->paginate(10);
        return view('user.index', compact('users'));
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if ($user->role->name == 'Admin') {
            return redirect()->back()->with('error', 'Cannot delete admin user');
        }

        try {
            $user->votes()->delete();
            $user->comments()->delete();
            $user_feedbacks = $user->feedback;

            foreach ($user_feedbacks as $feedback) {
                $feedback->votes()->delete();
                $feedback->comments()->delete();
                $feedback->delete();
            }
            $user->delete();
            return redirect()->back()->with('status', 'User deleted successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Error deleting user ' . $th->getMessage());
        }
    }
}
