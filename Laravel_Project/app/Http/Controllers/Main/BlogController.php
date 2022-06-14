<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', 'blog')
            ->orderBy('title')
            ->get();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', 'blog')
            ->orderBy('title')
            ->get();

        $blogs = Blog::query()
            // Filter, Search
            ->when($request->input('submit'), function (Builder $query) use ($request) {
                // Filter <>
                // Category
                if ($category = $request->input('category'))
                    $query->where('category_id', '=', $category == 'none' ? null : $category);
                // Tags
                if ($tag = $request->input('tag'))
                    $query->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($tag) {
                        $query->select('tag_id', 'taggable_type', 'taggable_id')
                            ->from('taggables')
                            ->where('taggables.taggable_type', '=', 'App\Models\Blog')
                            ->where('taggables.tag_id', '=', $tag)
                            ->whereColumn('taggables.taggable_id', 'blogs.id');
                    });
                // Date
                if ($startDate = $request->input('startDate'))
                    $query->whereDate('created_at', '>=', $startDate);
                // Filter </>

                // Search <>
                // Search in title
                if ($searchTitle = $request->input('search_title'))
                    $query->where('title', 'LIKE', '%' . $searchTitle . '%');
                // Search </>

                return $query;
            })
            // Sort
            ->orderby('created_at', 'desc')
            ->paginate(8);


        return view('main.blog.index', compact([
            'categories',
            'tags',
            'blogs',
        ]));
    }

    public function show(Blog $blog)
    {
        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', 'blog')
            ->orderBy('title')
            ->get();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', 'blog')
            ->orderBy('title')
            ->get();

        $comments = $blog->comments()
            ->where('approved', '=', '1')
            ->get();

        $recentBlogs = Blog::query()
            ->where('id', '!=', $blog->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('main.blog.show', compact([
            'blog',
            'comments',
            'categories',
            'tags',
            'recentBlogs',
        ]));
    }
}
