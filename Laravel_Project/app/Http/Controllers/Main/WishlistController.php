<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // wishlists table is a pivot table between users and products

    public function index() {
        $products = Auth::user()->wishlistProducts()
            ->orderBy('wishlists.created_at', 'asc')
            ->get();

        return view('main.wishlist.index', compact([
            'products',
        ]));
    }

    public function add($productId)
    {
        if (Auth::user()->wishlistProducts->contains($productId))
            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Product is already in the wishlist.',
                        'type' => 'danger',
                    ],
                ]);

        Auth::user()->wishlistProducts()->attach($productId);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Product added to the wishlist successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function remove($productId)
    {
        Auth::user()->wishlistProducts()->detach($productId);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Product removed from the wishlist successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
