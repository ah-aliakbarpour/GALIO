<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
{
    // Define type using route name
    // Type can be 'product' or 'blog'
    // (route names are like 'admin.product.tag...' or 'admin.blog.tag...')
    protected function defineType()
    {
        try {
            $type = explode('.', Route::currentRouteName())[1];
        }
        catch (\Exception $exception) {
            abort(404);
        }
        finally {
            if ($type !== 'product' && $type !== 'blog')
                abort(404);
        }

        return $type;
    }

    public function index()
    {
        $type = $this->defineType();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', $type)
            ->orderBy('title')
            ->paginate(20);

        // First record index in each pagination page
        $index = $tags->firstItem();

        return view('admin.' . $type . '.tag.index', compact([
            'tags',
            'index',
        ]));
    }

    public function store(TagRequest $request)
    {
        $type = $this->defineType();

        Tag::query()->create([
            'type' => $type,
            'title' => $request->input('title'),
        ]);

        return redirect()->route('admin.' . $type . '.tag.index')
            ->with([
                'alert' => [
                    'massage' => 'Tag created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Tag $tag)
    {
        $type = $this->defineType();

        return view('admin.' . $type . '.tag.edit', compact([
            'tag',
        ]));
    }

    public function update(TagRequest $request, Tag $tag)
    {
        $type = $this->defineType();

        $tag->update([
            'title' => $request->input('title'),
        ]);

        return redirect()->route('admin.' . $type . '.tag.index')
            ->with([
                'alert' => [
                    'massage' => 'Tag edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Tag $tag)
    {
        $type = $this->defineType();

        $tag->delete();

        return redirect()->route('admin.' . $type . '.tag.index')
            ->with([
                'alert' => [
                    'massage' => 'Tag deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $type = $this->defineType();

        $tagsId = $request->input('tags_id');

        // No tag selected
        if (!$tagsId)
            throw ValidationException::withMessages(['selections' => 'No tag selected.']);

        Tag::destroy(array_keys($tagsId));

        return redirect()->route('admin.' . $type . '.tag.index')
            ->with([
                'alert' => [
                    'massage' => 'Tags deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
