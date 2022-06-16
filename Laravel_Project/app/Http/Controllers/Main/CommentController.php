<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\Main\CommentRequest;
use App\Models\Blog;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Blog $blog)
    {
        $blog->comments()->create([
            'context' => $request->input('context'),
            'author_name' => $request->input('name'),
            'author_email' => $request->input('email'),
        ]);

        return redirect()->route('blog.show', ['blog' => $blog->id])
            ->with([
                'alert' => [
                    'massage' => 'Your Comment submitted successfully and will be published after admin approval.',
                    'type' => 'success',
                ],
            ]);
    }
}
