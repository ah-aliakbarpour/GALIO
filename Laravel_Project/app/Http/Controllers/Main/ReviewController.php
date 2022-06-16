<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Product $product)
    {
        $product->reviews()->create([
            'rating' => $request->input('rating'),
            'context' => $request->input('context'),
            'author_name' => $request->input('name'),
            'author_email' => $request->input('email'),
        ]);

        return redirect()->route('product.show', ['product' => $product->id])
            ->with([
                'alert' => [
                    'massage' => 'Your review submitted successfully and will be published after admin approval.',
                    'type' => 'success',
                ],
            ]);
    }
}
