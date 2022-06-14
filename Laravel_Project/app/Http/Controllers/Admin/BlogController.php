<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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


        $created_atRange = Blog::query()
            ->select(DB::raw('MIN(created_at) As min'), DB::raw('MAX(created_at) AS max'))
            ->first();


        $blogs = Blog::query()
            // Filter, Search
            ->when($request->input('submit'), function (Builder $query) use ($request) {
                // Filter <>
                // Category
                if ($category = $request->input('category'))
                    $query->where('category_id', '=', $category == 'none' ? null : $category);
                // Tags
                if ($tags = $request->input('tags'))
                    if ($tags[0] == 'none' && count($tags) == 1)
                        $query->whereNotExists(function (\Illuminate\Database\Query\Builder $query) {
                            $query->select('taggable_type', 'taggable_id')
                                ->from('taggables')
                                ->where('taggables.taggable_type', '=', 'App\Models\Blog')
                                ->whereColumn('taggables.taggable_id', 'blogs.id');
                        });
                    else
                        foreach ($tags as $tag)
                            $query->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($tag) {
                                $query->select('tag_id', 'taggable_type', 'taggable_id')
                                    ->from('taggables')
                                    ->where('taggables.taggable_type', '=', 'App\Models\Blog')
                                    ->where('taggables.tag_id', '=', $tag)
                                    ->whereColumn('taggables.taggable_id', 'blogs.id');
                            });
                // Date
                if ($date = $request->input('date'))
                {
                    $dateRange = explode(' - ', $date);
                    $startDate = str_replace('/', '-', $dateRange[0]);
                    $endDate = str_replace('/', '-', $dateRange[1]);
                    $query->whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate);
                }
                // Filter </>

                // Search <>
                // Search in title
                if ($searchTitle = $request->input('search_title'))
                    $query->where('title', 'LIKE', '%' . $searchTitle . '%');
                // Search in author
                if ($searchAuthor = $request->input('search_author'))
                    $query->where('author', 'LIKE', '%' . $searchAuthor . '%');
                // Search in context
                if ($searchContext = $request->input('search_context'))
                    $query->where('context', 'LIKE', '%' . $searchContext . '%');
                // Search </>

                return $query;
            })
            // Sort
            ->when($request->input('submit') && $request->input('sort') != 'created_at,desc',
                function (Builder $query) use ($request) {
                    $sort = explode(',', $request->input('sort'));
                    $column = $sort[0];
                    $direction = $sort[1];
                    return $query->orderby($column, $direction);
                } , fn (Builder $query) => $query->orderby('created_at', 'desc'))
            ->paginate(20);

        return view('admin.blog.index', compact([
            'categories',
            'tags',
            'created_atRange',
            'blogs',
        ]));
    }

    public function show(Blog $blog)
    {
        // Pending comments
        $pendingComments = $blog->comments()
            ->where('approved', '=', '0')
            ->paginate(20, ['*'], 'pendingComments');

        // Pending comments | First record index in each pagination page
        $indexPendingComments = $pendingComments->firstItem();


        // Approved comments
        $approvedComments = $blog->comments()
            ->where('approved', '=', '1')
            ->paginate(20, ['*'], 'approvedComments');

        // Approved comments | First record index in each pagination page
        $indexApprovedComments = $approvedComments->firstItem();

        return view('admin.blog.show', compact([
            'blog',
            'pendingComments',
            'indexPendingComments',
            'approvedComments',
            'indexApprovedComments'
        ]));
    }

    public function create()
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

        return view('admin.blog.create', compact([
            'categories',
            'tags',
        ]));
    }

    public function store(BlogRequest $request)
    {
        // Create blog
        $blog = Blog::query()->create([
            'category_id' => $request->input('category') == 'none' ? null : $request->input('category'),
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'context' => $request->input('context'),
        ]);

        // Images
        if($request->hasFile('images'))
            foreach ($request->file('images') as $image)
            {
                $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();

                $image->storeAs(Blog::IMAGES_DIR, $imageName, ['disk' => 'public']);

                $blog->images()->create([
                    'name' => $imageName,
                ]);
            }

        // Tags
        if ($request->input('tags'))
            $blog->tags()->attach($request->input('tags'));

        return redirect()->route('admin.blog.index')
            ->with([
                'alert' => [
                    'massage' => 'Blog created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Blog $blog)
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

        $blogTagsId = [];
        foreach ($blog->tags as $tag)
            $blogTagsId[] = $tag->id;

        return view('admin.blog.edit', compact([
            'blog',
            'blogTagsId',
            'categories',
            'tags',
        ]));
    }

    public function deleteImages(Blog $blog)
    {
        $blog->deleteImages();

        // Touch blog updated_at attribute
        $blog->touch();

        return redirect()->route('admin.blog.edit', ['blog' => $blog->id])
            ->with([
                'alert' => [
                    'massage' => 'Images deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }


    public function update(BlogRequest $request, Blog $blog)
    {
        // Update blog
        $blog->update([
            'category_id' => $request->input('category') == 'none' ? null : $request->input('category'),
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'context' => $request->input('context'),
        ]);

        // Images
        if($request->hasFile('images'))
            foreach ($request->file('images') as $image)
            {
                $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();

                $image->storeAs(Blog::IMAGES_DIR, $imageName, ['disk' => 'public']);

                $blog->images()->create([
                    'name' => $imageName,
                ]);
            }

        // Tags <>
        // Detach all old tags from the blog
        $blog->tags()->detach();

        // Attach new tags to the blog
        if ($request->input('tags'))
            $blog->tags()->attach($request->input('tags'));
        // tags </>

        // Touch blog updated_at attribute
        $blog->touch();

        return redirect()->route('admin.blog.index')
            ->with([
                'alert' => [
                    'massage' => 'Blog edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Blog $blog)
    {
        $blog->deleteCompletely();

        return redirect()->route('admin.blog.index')
            ->with([
                'alert' => [
                    'massage' => 'Blog deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $blogsId = $request->input('blogs_id');

        // No blog selected
        if (!$blogsId)
            throw ValidationException::withMessages(['selections' => 'No blog selected.']);

        foreach (array_keys($blogsId) as $id)
            Blog::query()->find($id)->deleteCompletely();

        return redirect()->route('admin.blog.index')
            ->with([
                'alert' => [
                    'massage' => 'Blogs deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
