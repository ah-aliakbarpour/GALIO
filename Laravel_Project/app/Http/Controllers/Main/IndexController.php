<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->orderBy('created_at', 'desc')
            ->get();

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

        $mostRatedProducts = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            ->whereNotNull('rating')
            ->where('stock', '>',  '0')
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get();

        $discountedProducts = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            ->where('stock', '>',  '0')
            ->whereNotNull('discount')
            ->orderBy('discount', 'desc')
            ->limit(6)
            ->get();

        $newProducts = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            ->where('stock', '>',  '0')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $featuredProducts = Product::query()
            ->select('products.*', DB::raw('ROUND(AVG(reviews.rating), 1) AS rating'))
            ->leftJoin('reviews', function ($join) {
                $join->on('products.id', '=', 'reviews.product_id')
                    ->where('reviews.approved', '=', '1');
            })
            ->groupBy('products.id')
            ->where('stock', '>',  '0')
            //->whereNotNull('discount')
            //->orWhereNotNull('rating')
            ->orderBy('created_at', 'desc')
            ->limit(16)
            ->get();


        $latestBlogs = Blog::query()
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();


        return view('main.index', compact([
            'banners',
            'advertisements',
            'mostRatedProducts',
            'discountedProducts',
            'newProducts',
            'featuredProducts',
            'latestBlogs',
        ]));
    }
}
