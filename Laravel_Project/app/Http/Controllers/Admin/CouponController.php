<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use Egulias\EmailValidator\Exception\CommaInDomain;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::query()
            ->paginate(20);

        // First record index in each pagination page
        $index = $coupons->firstItem();

        return view('admin.coupon.index', compact([
            'coupons',
            'index',
        ]));
    }

    public function store(CouponRequest $request)
    {
        Coupon::query()->create([
            'code' => $request->input('code'),
            'price' => $request->input('price'),
        ]);

        return redirect()->route('admin.coupon.index')
            ->with([
                'alert' => [
                    'massage' => 'Coupon created successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupon.edit', compact([
            'coupon',
        ]));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update([
            'code' => $request->input('code'),
            'price' => $request->input('price'),
        ]);

        return redirect()->route('admin.coupon.index')
            ->with([
                'alert' => [
                    'massage' => 'Coupon edited successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupon.index')
            ->with([
                'alert' => [
                    'massage' => 'Coupon deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function selections(Request $request)
    {
        $couponsId = $request->input('coupons_id');

        // No coupon selected
        if (!$couponsId)
            throw ValidationException::withMessages(['selections' => 'No coupon selected.']);

        Coupon::destroy(array_keys($couponsId));

        return redirect()->route('admin.coupon.index')
            ->with([
                'alert' => [
                    'massage' => 'Coupons deleted successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
