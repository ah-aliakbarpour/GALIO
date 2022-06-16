<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    // Define type using route name
    // Type can be 'product' or 'blog'
    // (route names are like 'admin.product.category...' or 'admin.blog.category...')
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

        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', $type)
            ->orderBy('title')
            ->paginate(20);

        // First record index in each pagination page
        $index = $categories->firstItem();

        return view('admin.' . $type . '.category.index', compact([
            'categories',
            'index',
        ]));
    }

    public function store(CategoryRequest $request)
    {
        $type = $this->defineType();

        Category::query()->create([
            'type' => $type,
            'title' => $request->input('title'),
        ]);

        return redirect()->route('admin.' . $type . '.category.index')
            ->with([
                'alert' => [
                    'massage' => 'Category created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Category $category)
    {
        $type = $this->defineType();

        return view('admin.' . $type . '.category.edit', compact([
            'category',
        ]));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $type = $this->defineType();

        $category->update([
            'title' => $request->input('title'),
        ]);

        return redirect()->route('admin.' . $type . '.category.index')
            ->with([
                'alert' => [
                    'massage' => 'Category edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Category $category)
    {
        $type = $this->defineType();

        $category->delete();

        return redirect()->route('admin.' . $type . '.category.index')
            ->with([
                'alert' => [
                    'massage' => 'Category deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $type = $this->defineType();

        $categoriesId = $request->input('categories_id');

        // No category selected
        if (!$categoriesId)
            throw ValidationException::withMessages(['selections' => 'No category selected.']);

        Category::destroy(array_keys($categoriesId));

        return redirect()->route('admin.' . $type . '.category.index')
            ->with([
                'alert' => [
                    'massage' => 'Categories deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
