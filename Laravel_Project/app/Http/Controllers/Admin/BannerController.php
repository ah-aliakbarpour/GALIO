<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.banner.index', compact([
            'banners',
        ]));
    }

    public function show(Banner $banner)
    {
        return view('admin.banner.show', compact([
            'banner',
        ]));
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function store(BannerRequest $request)
    {
        // Images <>
        $image = $request->file('image');
        $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();
        $image->storeAs(Banner::IMAGES_DIR, $imageName, ['disk' => 'public']);
        // Image </>

        Banner::query()->create([
            'image_name' => $imageName,
            'first_header' => $request->input('first_header'),
            'second_header' => $request->input('second_header'),
            'paragraph' => $request->input('paragraph'),
            'link' => $request->input('link'),
        ]);

        return redirect()->route('admin.banner.index')
            ->with([
                'alert' => [
                    'massage' => 'Banner created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact([
            'banner'
        ]));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        // Images <>
        if($request->hasFile('image'))
        {
            $banner->deleteImageFile();

            $image = $request->file('image');
            $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();
            $image->storeAs(Banner::IMAGES_DIR, $imageName, ['disk' => 'public']);

            $banner->update([
                'image_name' => $imageName,
            ]);
        }
        // Image </>

        $banner->update([
            'first_header' => $request->input('first_header'),
            'second_header' => $request->input('second_header'),
            'paragraph' => $request->input('paragraph'),
            'link' => $request->input('link'),
        ]);

        return redirect()->route('admin.banner.index')
            ->with([
                'alert' => [
                    'massage' => 'Banner edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Banner $banner)
    {
        $banner->deleteCompletely();

        return redirect()->route('admin.banner.index')
            ->with([
                'alert' => [
                    'massage' => 'Banner deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $bannersId = $request->input('banners_id');

        // No product selected
        if (!$bannersId)
            throw ValidationException::withMessages(['selections' => 'No banner selected.']);

        foreach (array_keys($bannersId) as $id)
            Banner::query()->find($id)->deleteCompletely();

        return redirect()->route('admin.banner.index')
            ->with([
                'alert' => [
                    'massage' => 'Banners deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
