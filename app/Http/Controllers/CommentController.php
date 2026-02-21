<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Task $task): RedirectResponse
    {
        $request->validate(['body' => 'required|string|max:5000']);

        $task->comments()->create([
            'body'    => $request->body,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Комментарий добавлен.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        if (auth()->id() !== $comment->user_id && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $taskId = $comment->task_id;
        $comment->delete();

        return back()->with('success', 'Комментарий удалён.');
    }
}
