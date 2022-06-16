<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function index()
    {
        // Pending reviews
        $pendingReviews = Review::query()
            ->where('approved', '=', '0')
            ->orderBy('created_at')
            ->paginate(20, ['*'], 'pendingReviews');

        // Pending reviews | First record index in each pagination page
        $indexPendingReviews = $pendingReviews->firstItem();


        // Approved reviews
        $approvedReviews = Review::query()
            ->where('approved', '=', '1')
            ->orderBy('approved_at', 'desc')
            ->paginate(20, ['*'], 'approvedReviews');

        // Approved reviews | First record index in each pagination page
        $indexApprovedReviews = $approvedReviews->firstItem();

        return view('admin.product.review.index', compact([
            'pendingReviews',
            'indexPendingReviews',
            'approvedReviews',
            'indexApprovedReviews'
        ]));
    }

    public function show(Review $review)
    {
        return view('admin.product.review.show', compact([
            'review',
        ]));
    }

    public function approve(Review $review)
    {
        $review->approve();

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Review Approved successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        $previousRoutName = app('router')->getRoutes()
            ->match(app('request')->create(URL::previous()))->getName();

        // Can't redirect back to this route('admin.product.review.show') because after deleting
        // this review route will not exist.
        if ($previousRoutName == 'admin.product.review.show')
            return redirect()->route('admin.product.review.index')
                ->with([
                    'alert' => [
                        'massage' => 'Review deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
        else
            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Review deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
    }

    public function selections(Request $request)
    {
        $reviewsId = $request->input('reviews_id');

        $type = $request->input('type');

        $action = $request->input('submit');

        // No review selected
        if (!$reviewsId)
            throw ValidationException::withMessages(['selections_' . $type => 'No reviews selected.']);


        if ($action == 'Delete')
        {
            Review::destroy(array_keys($reviewsId));

            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Reviews deleted successfully.',
                        'type' => 'success',
                    ],
                ]);
        }

        if ($action == 'Approve')
        {
            foreach (array_keys($reviewsId) as $id)
                Review::query()->find($id)->approve();

            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Reviews approved successfully.',
                        'type' => 'success',
                    ],
                ]);
        }

        abort(404);
    }
}
