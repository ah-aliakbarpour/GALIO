<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function index()
    {
        // Pending comments
        $pendingComments = Comment::query()
            ->where('approved', '=', '0')
            ->paginate(20, ['*'], 'pendingComments');

        // Pending comments | First record index in each pagination page
        $indexPendingComments = $pendingComments->firstItem();


        // Approved comments
        $approvedComments = Comment::query()
            ->where('approved', '=', '1')
            ->paginate(20, ['*'], 'approvedComments');

        // Approved comments | First record index in each pagination page
        $indexApprovedComments = $approvedComments->firstItem();

        return view('admin.blog.comment.index', compact([
            'pendingComments',
            'indexPendingComments',
            'approvedComments',
            'indexApprovedComments'
        ]));
    }

    public function show(Comment $comment)
    {
        return view('admin.blog.comment.show', compact([
            'comment',
        ]));
    }

    public function approve(Comment $comment)
    {
        $comment->approve();

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Comment Approved successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        $previousRoutName = app('router')->getRoutes()
            ->match(app('request')->create(URL::previous()))->getName();

        // Can't redirect back to this route('admin.blog.comment.show') because after deleting
        // this comment route will not exist.
        if ($previousRoutName == 'admin.blog.comment.show')
            return redirect()->route('admin.blog.comment.index')
                ->with([
                    'alert' => [
                        'massage' => 'Comment deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
        else
            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Comment deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
    }

    public function selections(Request $request)
    {
        $commentsId = $request->input('comments_id');

        $type = $request->input('type');

        $action = $request->input('submit');

        // No comment selected
        if (!$commentsId)
            throw ValidationException::withMessages(['selections_' . $type => 'No comments selected.']);


        if ($action == 'Delete')
        {
            Comment::destroy(array_keys($commentsId));

            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Comments deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
        }

        if ($action == 'Approve')
        {
            foreach (array_keys($commentsId) as $id)
                Comment::query()->find($id)->approve();

            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Comments approved successfully.',
                        'type' => 'success',
                    ],
                ]);
        }

        abort(404);
    }
}
