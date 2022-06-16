<?php


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;


Route::fallback(function () {
    return view('admin.404');
})->name('fallback');


Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


// Profile <>
Route::get('/profile', [AdminProfileController::class, 'profile'])
    ->name('profile');

Route::patch('/profile/save-changes', [AdminProfileController::class, 'save_changes'])
    ->name('profile.save-changes');

Route::patch('/profile/change-password', [AdminProfileController::class, 'change_password'])
    ->name('profile.change-password');
// Profile </>


// Product <>
Route::prefix('product')
    ->name('product.')
    ->group(function () {
        // Category
        Route::post('/category/selections', [CategoryController::class, 'selections'])
            ->name('category.selections');

        Route::resource('category', CategoryController::class)
            ->except(['show', 'create']);

        // Tag
        Route::post('/tag/selections', [TagController::class, 'selections'])
            ->name('tag.selections');

        Route::resource('tag', TagController::class)
            ->except(['show', 'create']);

        // Review
        Route::post('/review/{review}/approve', [ReviewController::class, 'approve'])
            ->name('review.approve');

        Route::post('/review/selections', [ReviewController::class, 'selections'])
            ->name('review.selections');

        Route::resource('review', ReviewController::class)
            ->only(['index', 'show', 'destroy']);
    });

Route::delete('/product/{product}/delete-images', [ProductController::class, 'deleteImages'])
    ->name('product.delete-images');

Route::post('/product/selections', [ProductController::class, 'selections'])
    ->name('product.selections');

Route::resource('product', ProductController::class);
// Product </>


// Blog <>
Route::prefix('blog')
    ->name('blog.')
    ->group(function () {
        // Category
        Route::post('/category/selections', [CategoryController::class, 'selections'])
            ->name('category.selections');

        Route::resource('category', CategoryController::class)
            ->except(['show', 'create']);

        // Tag
        Route::post('/tag/selections', [TagController::class, 'selections'])
            ->name('tag.selections');

        Route::resource('tag', TagController::class)
            ->except(['show', 'create']);

        // Comment
        Route::post('/comment/{comment}/approve', [CommentController::class, 'approve'])
            ->name('comment.approve');

        Route::post('/comment/selections', [CommentController::class, 'selections'])
            ->name('comment.selections');

        Route::resource('comment', CommentController::class)
            ->only(['index', 'show', 'destroy']);
    });

Route::delete('/blog/{blog}/delete-images', [BlogController::class, 'deleteImages'])
    ->name('blog.delete-images');

Route::post('/blog/selections', [BlogController::class, 'selections'])
    ->name('blog.selections');

Route::resource('blog', BlogController::class);
// Blog </>


// Coupon <>
Route::post('/coupon/selections', [CouponController::class, 'selections'])
    ->name('coupon.selections');

Route::resource('coupon', CouponController::class)
    ->except(['show', 'create']);
// Coupon </>


// Admin <>
Route::middleware(['main_admin'])->group(function () {
    Route::post('/admin/selections', [AdminController::class, 'selections'])
        ->name('admin.selections');

    Route::resource('admin', AdminController::class);
});
// Admin </>


// Banner <>
Route::post('/banner/selections', [BannerController::class, 'selections'])
    ->name('banner.selections');

Route::resource('banner', BannerController::class);
// Banner </>


// Advertisements <>
Route::resource('advertisement', AdvertisementController::class)
    ->except(['show']);
// Advertisements </>
