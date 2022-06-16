<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            ->select(DB::raw('TRUNCATE(MIN(price), 0) As min'), DB::raw('TRUNCATE(MAX(price), 0) + 1 AS max'))
            ->first();

        $products = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            // Filter
            ->when($request->input('submit') == 'Filter', function (Builder $query) use ($request, $priceRange) {
                // Category
                if ($category = $request->input('category'))
                    $query->where('category_id', '=', $category == 'none' ? null : $category);
                // Tags
                if ($tag = $request->input('tag'))
                    $query->whereExists(function (\Illuminate\Database\Query\Builder $query) use ($tag) {
                        $query->select('tag_id', 'taggable_type', 'taggable_id')
                            ->from('taggables')
                            ->where('taggables.taggable_type', '=', 'App\Models\Product')
                            ->where('taggables.tag_id', '=', $tag)
                            ->whereColumn('taggables.taggable_id', 'products.id');
                    });
                // Stock
                if ($request->input('in_stock'))
                    $query->where('stock', '!=', 0);
                // Price
                if ($request->input('price'))
                {
                    $inputPriceRange = explode(' - ', str_replace('$', '', $request->input('price')));
                    $inputMinPrice = $inputPriceRange[0];
                    $inputMaxPrice = $inputPriceRange[1];
                    if ($inputMinPrice != $priceRange->min || $inputMaxPrice != $priceRange->max)
                        $query->whereBetween('price', [$inputMinPrice, $inputMaxPrice]);
                }

                return $query;
            })
            // Search
            ->when($request->input('submit') == 'Search', function (Builder $query) use ($request) {
                // Search in title
                if ($searchTitle = $request->input('search_title'))
                    $query->where('title', 'LIKE', '%' . $searchTitle . '%');
            })
            // Sort
            ->when($request->input('sort') && $request->input('sort') != 'created_at,desc',
                function (Builder $query) use ($request) {
                    $sort = explode(',', $request->input('sort'));
                    $column = $sort[0];
                    $direction = $sort[1];
                    return $query->orderby($column, $direction);
                } , fn (Builder $query) => $query->orderby('created_at', 'desc'))
            ->paginate(20);


        $advertisement_5 = Advertisement::query()
            ->where('position','=', '5')
            ->first();

        $advertisement_6 = Advertisement::query()
            ->where('position','=', '6')
            ->first();


        return view('main.product.index', compact([
            'categories',
            'tags',
            'products',
            'priceRange',
            'advertisement_5',
            'advertisement_6',
        ]));
    }

    public function show(Product $product)
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

        $reviews = $product->reviews()
            ->where('approved', '=', '1')
            ->orderBy('approved_at', 'desc')
            ->get();

        $newProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('stock', '>',  '0')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->where('stock', '>',  '0')
            ->where('category_id', '=', $product->category_id)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        $advertisement = Advertisement::query()
            ->where('position','=', '6')
            ->first();

        return view('main.product.show', compact([
            'product',
            'reviews',
            'categories',
            'tags',
            'newProducts',
            'relatedProducts',
            'advertisement',
        ]));
    }
}
