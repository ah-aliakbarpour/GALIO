<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout()
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

        return view('main.order.checkout', compact([
            'coupon',
            'subtotalPrice',
            'shipping',
        ]));
    }
}
