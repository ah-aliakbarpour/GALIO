<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = [
            1 => Advertisement::query()
                ->where('position', '=', '1')
                ->first(),
            2 => Advertisement::query()
                ->where('position', '=', '2')
                ->first(),
            3 => Advertisement::query()
                ->where('position', '=', '3')
                ->first(),
            4 => Advertisement::query()
                ->where('position', '=', '4')
                ->first(),
            5 => Advertisement::query()
                ->where('position', '=', '5')
                ->first(),
            6 => Advertisement::query()
                ->where('position', '=', '6')
                ->first(),
        ];

        return view('admin.advertisement.index', compact([
            'advertisements',
        ]));
    }

    public function store(AdvertisementRequest $request)
    {
        $position = $request->input('position');

        // Images <>
        $image = $request->file('image_' . $position);
        $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();
        $image->storeAs(Advertisement::IMAGES_DIR, $imageName, ['disk' => 'public']);
        // Image </>

        Advertisement::query()->create([
            'position' => $position,
            'image_name' => $imageName,
            'link' => $request->input('link_' . $position),
        ]);

        return redirect()->route('admin.advertisement.index')
            ->with([
                'alert' => [
                    'massage' => 'Advertisement created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function update(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $position = $request->input('position');

        // Images <>
        if($request->hasFile('image_' . $position))
        {
            $advertisement->deleteImageFile();

            $image = $request->file('image_' . $position);
            $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();
            $image->storeAs(Advertisement::IMAGES_DIR, $imageName, ['disk' => 'public']);

            $advertisement->update([
                'image_name' => $imageName,
            ]);
        }
        // Image </>

        $advertisement->update([
            'position' => $position,
            'link' => $request->input('link_' . $position),
        ]);

        return redirect()->route('admin.advertisement.index')
            ->with([
                'alert' => [
                    'massage' => 'Advertisement edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Advertisement $advertisement)
    {
        $advertisement->deleteCompletely();

        return redirect()->route('admin.advertisement.index')
            ->with([
                'alert' => [
                    'massage' => 'Advertisement deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
