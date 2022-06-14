<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;

        $coupon = $cart->coupon;

        $products = $cart->products()
            ->orderBy('cart_product.created_at', 'asc')
            ->get();

        $subtotalPrice = 0;
        foreach ($products as $product)
            $subtotalPrice += ($product->price * (100 - $product->discount ?? 0) / 100) * $product->pivot->quantity;

        $shipping = Cart::SHIPPING_PRICE;

        return view('main.cart.index', compact([
            'coupon',
            'products',
            'subtotalPrice',
            'shipping',
        ]));
    }

    public function add($productId)
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$user->cart()->exists())
            $user->cart()->create([]);

        if ($cart->products->contains($productId))
        {
            $product = $cart->products
                ->where('id', '=', $productId)
                ->first();

            $quantity = $product->pivot->quantity;

            if ($quantity + 1 > $product->stock)
                return redirect()->back()
                    ->with([
                        'alert' => [
                            'massage' => 'Action isn\'t allowed.',
                            'type' => 'danger',
                        ],
                    ]);

            $cart->products()->updateExistingPivot($productId, [
                'quantity' => ++$quantity,
            ]);
        }
        else
            $cart->products()->attach($productId);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Product added to the cart successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function reduce($productId)
    {
        $cart = Auth::user()->cart;

        $quantity = $cart->products
            ->where('id', '=', $productId)
            ->first()
            ->pivot->quantity;

        if ($quantity <= 1)
            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'Action isn\'t allowed.',
                        'type' => 'danger',
                    ],
                ]);

        $cart->products()->updateExistingPivot($productId, [
            'quantity' => --$quantity,
        ]);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Product reduced from the cart successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function remove($productId)
    {
        Auth::user()->cart->products()->detach($productId);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Product removed from the cart successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $coupon = Coupon::query()
            ->where('code', '=', $request->input('code'))
            ->first();

        // check if coupon exists in database
        if ($coupon)
            Auth::user()->cart()->update([
                'coupon' => $coupon->price,
            ]);
        else
            return redirect()->back()
                ->with([
                    'alert' => [
                        'massage' => 'There isn\'t any coupon with this code.',
                        'type' => 'danger',
                    ],
                ]);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Coupon applied to cart successfully.',
                    'type' => 'success',
                ],
            ]);
    }

    public function removeCoupon()
    {
        Auth::user()->cart()->update([
            'coupon' => null,
        ]);

        return redirect()->back()
            ->with([
                'alert' => [
                    'massage' => 'Coupon removed from cart successfully.',
                    'type' => 'success',
                ],
            ]);
    }
}
