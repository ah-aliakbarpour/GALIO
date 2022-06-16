<?php


use App\Http\Controllers\Main\BlogController;
use App\Http\Controllers\Main\CartController;
use App\Http\Controllers\Main\CommentController;
use App\Http\Controllers\Main\IndexController;
use App\Http\Controllers\Main\OrderController;
use App\Http\Controllers\Main\ProductController;
use App\Http\Controllers\Main\ReviewController;
use App\Http\Controllers\Main\WishlistController;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('', [IndexController::class, 'index'])
    ->name('index');


Route::get('about-us', function () {
    return view('main.pages.about-us');
})->name('about-us');

Route::get('contact-us', function () {
    return view('main.pages.contact-us');
})->name('contact-us');



// Product <>
Route::get('/product', [ProductController::class, 'index'])
    ->name('product.index');

Route::get('/product/{product}', [ProductController::class, 'show'])
    ->name('product.show');

// Review
Route::post('product/{product}/review', [ReviewController::class, 'store'])
    ->name('product.review.store');
// Product </>



// Blog <>
Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{blog}', [BlogController::class, 'show'])
    ->name('blog.show');

// Comment
Route::post('blog/{blog}/comment', [CommentController::class, 'store'])
    ->name('blog.comment.store');
// Blog </>


// Subscribers
Route::post('subscribe', function (Request $request) {
    $request->validate([
        'email' => 'bail|required|email|unique:subscribers,email',
    ]);

    Subscriber::query()->create([
        'email' => $request->input('email'),
    ]);

    return redirect()->back()
        ->with([
            'alert' => [
                'massage' => 'Thanks for subscribing.',
                'type' => 'success',
            ],
        ]);
})->name('subscribe');


// Middleware: 'auth'
Route::middleware(['auth'])->group(function () {
    // Wishlists <>
    // wishlists table is a pivot table between users and products
    Route::get('wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('wishlist/{product}/add', [WishlistController::class, 'add'])
        ->name('wishlist.add');

    Route::delete('wishlist/{product}/remove', [WishlistController::class, 'remove'])
        ->name('wishlist.remove');
    // Wishlists </>

    // Carts <>
    Route::get('cart', [CartController::class, 'index'])
        ->name('cart.index');

    Route::post('cart/{product}/add', [CartController::class, 'add'])
        ->name('cart.add');

    Route::post('cart/{product}/reduce', [CartController::class, 'reduce'])
        ->name('cart.reduce');

    Route::delete('cart/{product}/remove', [CartController::class, 'remove'])
        ->name('cart.remove');

    Route::patch('cart/coupon/apply', [CartController::class, 'applyCoupon'])
        ->name('cart.coupon.apply');

    Route::patch('cart/coupon/remove', [CartController::class, 'removeCoupon'])
        ->name('cart.coupon.remove');
    // Carts </>

    // Orders </>
    Route::get('checkout', [OrderController::class, 'checkout'])
        ->name('checkout');
});
