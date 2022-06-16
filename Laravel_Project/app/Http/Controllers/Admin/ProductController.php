<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();


        $priceRange = Product::query()
            ->select(DB::raw('MIN(price) As min'), DB::raw('MAX(price) AS max'))
            ->first();

        $created_atRange = Product::query()
            ->select(DB::raw('MIN(created_at) As min'), DB::raw('MAX(created_at) AS max'))
            ->first();


        $products = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            // Filter, Search
            ->when($request->input('submit'), function (Builder $query) use ($request, $priceRange) {
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
                                ->where('taggables.taggable_type', '=', 'App\Models\Product')
                                ->whereColumn('taggables.taggable_id', 'products.id');
                        });
                    else
                        foreach ($tags as $tag)
                            $query->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($tag) {
                                $query->select('tag_id', 'taggable_type', 'taggable_id')
                                    ->from('taggables')
                                    ->where('taggables.taggable_type', '=', 'App\Models\Product')
                                    ->where('taggables.tag_id', '=', $tag)
                                    ->whereColumn('taggables.taggable_id', 'products.id');
                            });
                // Stock
                if ($request->input('stock') == 'in_stock')
                    $query->where('stock', '!=', 0);
                elseif ($request->input('stock') == 'out_of_stock')
                    $query->where('stock', '=', 0);
                // Price
                $inputPriceRange = explode(',', $request->input('price'));
                $inputMinPrice = $inputPriceRange[0];
                $inputMaxPrice = $inputPriceRange[1];
                if ($inputMinPrice != $priceRange->min || $inputMaxPrice != $priceRange->max)
                    $query->whereBetween('price', [$inputMinPrice, $inputMaxPrice]);
                // Discount
                $inputDiscountRange = explode(',', $request->input('discount'));
                $inputMinDiscount = $inputDiscountRange[0];
                $inputMaxDiscount = $inputDiscountRange[1];
                if ($inputMinDiscount != 0 || $inputMaxDiscount != 100)
                {
                    $query->whereBetween('discount', [$inputMinDiscount, $inputMaxDiscount]);
                    if ($inputMinDiscount == 0)
                        $query->orWhereNull('discount');
                }
                // Date
                if ($date = $request->input('date'))
                {
                    $dateRange = explode(' - ', $date);
                    $startDate = str_replace('/', '-', $dateRange[0]);
                    $endDate = str_replace('/', '-', $dateRange[1]);
                    $query->whereDate('products.created_at', '>=', $startDate)
                        ->whereDate('products.created_at', '<=', $endDate);
                }
                // Rating
                $inputRatingRange = explode(',', $request->input('rating'));
                $inputMinRating = $inputRatingRange[0];
                $inputMaxRating = $inputRatingRange[1];
                if ($inputMinRating != 0 || $inputMaxRating != 5)
                    $query->havingBetween('rating', [$inputMinRating, $inputMaxRating]);
                // Filter </>

                // Search <>
                // Search in title
                if ($searchTitle = $request->input('search_title'))
                    $query->where('title', 'LIKE', '%' . $searchTitle . '%');
                // Search in introduction
                if ($searchIntroduction = $request->input('search_introduction'))
                    $query->where('introduction', 'LIKE', '%' . $searchIntroduction . '%');
                // Search in description
                if ($searchDescription = $request->input('search_description'))
                    $query->where('description', 'LIKE', '%' . $searchDescription . '%');
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

        return view('admin.product.index', compact([
            'priceRange',
            'created_atRange',
            'categories',
            'tags',
            'products',
        ]));
    }

    public function show(Product $product)
    {
        // Pending reviews
        $pendingReviews = $product->reviews()
            ->where('approved', '=', '0')
            ->orderBy('created_at')
            ->paginate(20, ['*'], 'pendingReviews');

        // Pending reviews | First record index in each pagination page
        $indexPendingReviews = $pendingReviews->firstItem();


        // Approved reviews
        $approvedReviews = $product->reviews()
            ->where('approved', '=', '1')
            ->orderBy('approved_at', 'desc')
            ->paginate(20, ['*'], 'approvedReviews');

        // Approved reviews | First record index in each pagination page
        $indexApprovedReviews = $approvedReviews->firstItem();

        return view('admin.product.show', compact([
            'product',
            'pendingReviews',
            'indexPendingReviews',
            'approvedReviews',
            'indexApprovedReviews'
        ]));
    }

    public function create()
    {
        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();

        return view('admin.product.create', compact([
            'categories',
            'tags',
        ]));
    }

    public function store(ProductRequest $request)
    {
        // Create product
        $product = Product::query()->create([
            'category_id' => $request->input('category') == 'none' ? null : $request->input('category'),
            'title' => $request->input('title'),
            'introduction' => $request->input('introduction'),
            'description' => $request->input('description'),
            'stock' => $request->input('stock'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
        ]);

        // Information
        if ($information = $request->input('information'))
            foreach ($information['keys'] as $i => $j)
                $product->information()->create([
                    'key' => $information['keys'][$i],
                    'value' => $information['values'][$i],
                ]);

        // Images
        if($request->hasFile('images'))
            foreach ($request->file('images') as $image)
            {
                $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();

                $image->storeAs(Product::IMAGES_DIR, $imageName, ['disk' => 'public']);

                $product->images()->create([
                    'name' => $imageName,
                ]);
            }

        // Tags
        if ($request->input('tags'))
            $product->tags()->attach($request->input('tags'));

        return redirect()->route('admin.product.index')
            ->with([
                'alert' => [
                    'massage' => 'Product created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Product $product)
    {
        $categories = Category::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();

        $tags = Tag::query()
            ->select('id', 'title')
            ->where('type', '=', 'product')
            ->orderBy('title')
            ->get();

        $productTagsId = [];
        foreach ($product->tags as $tag)
            $productTagsId[] = $tag->id;

        return view('admin.product.edit', compact([
            'product',
            'productTagsId',
            'categories',
            'tags',
        ]));
    }

    public function deleteImages(Product $product)
    {
        $product->deleteImages();

        // Touch product updated_at attribute
        $product->touch();

        return redirect()->route('admin.product.edit', ['product' => $product->id])
            ->with([
                'alert' => [
                    'massage' => 'Images deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        // Update product
        $product->update([
            'category_id' => $request->input('category') == 'none' ? null : $request->input('category'),
            'title' => $request->input('title'),
            'introduction' => $request->input('introduction'),
            'description' => $request->input('description'),
            'stock' => $request->input('stock'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
        ]);

        // Information <>
        // Delete old information
        $product->information()->delete();

        // Create new information
        if ($information = $request->input('information'))
            foreach ($information['keys'] as $i => $j)
                $product->information()->create([
                    'key' => $information['keys'][$i],
                    'value' => $information['values'][$i],
                ]);
        // Information </>

        // Images
        if($request->hasFile('images'))
            foreach ($request->file('images') as $image)
            {
                $imageName = time() . '-' . Str::random(10) . '.' . $image->extension();

                $image->storeAs(Product::IMAGES_DIR, $imageName, ['disk' => 'public']);

                $product->images()->create([
                    'name' => $imageName,
                ]);
            }

        // Tags <>
        // Detach all old tags from the product
        $product->tags()->detach();

        // Attach new tags to the product
        if ($request->input('tags'))
            $product->tags()->attach($request->input('tags'));
        // tags </>

        // Touch product updated_at attribute
        $product->touch();

        return redirect()->route('admin.product.index')
            ->with([
                'alert' => [
                    'massage' => 'Product edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Product $product)
    {
        $product->deleteCompletely();

        return redirect()->route('admin.product.index')
            ->with([
                'alert' => [
                    'massage' => 'Product deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $productsId = $request->input('products_id');

        // No product selected
        if (!$productsId)
            throw ValidationException::withMessages(['selections' => 'No product selected.']);

        foreach (array_keys($productsId) as $id)
            Product::query()->find($id)->deleteCompletely();

        return redirect()->route('admin.product.index')
            ->with([
                'alert' => [
                    'massage' => 'Products deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
