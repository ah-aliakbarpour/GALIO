@extends('main.layouts.app')



@section('title', 'Cart')



@section('content')


    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Cart</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- Alert -->
    @if(Session()->exists('alert'))
        <div class="container mb-10">
            <div class="row">
                <div class="col-md-12">
                    <p class="alert alert-{{ Session()->get('alert')['type'] }}">
                        {{ Session()->get('alert')['massage'] }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="cart-main-wrapper">
        <div class="container">
            @if($products->isNotEmpty())
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Image</th>
                                        <th class="pro-title">Product</th>
                                        <th class="pro-price">Price</th>
                                        <th class="pro-quantity">Quantity</th>
                                        <th class="pro-subtotal">Total</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="pro-thumbnail">
                                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    @if($product->images->isNotEmpty())
                                                        <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 99px; width: 99px; object-fit: contain" class="img-fluid">
                                                    @else
                                                        <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 99px; width: 99px; object-fit: contain" class="img-fluid">
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="pro-title">
                                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    {{ \Illuminate\Support\Str::limit($product->title, 50   , '...') }}
                                                </a>
                                            </td>
                                            @if($product->stock)
                                                <td class="pro-price">
                                                    <span>${{ number_format($product->price * (100 - $product->discount ?? 0) / 100, 2) }}</span>
                                                </td>
                                                <td class="pro-quantity">
                                                    <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="post">
                                                        @csrf
                                                        <input type="submit" name="submit" id="{{ 'cart_submit_add_' . $product->id }}" value="submit" style="display: none">
                                                    </form>

                                                    <div class="pro-qty">
                                                        <input type="text" class="quantity" value="{{ $product->pivot->quantity }}">
                                                        <input type="hidden" class="product_id" value="{{ $product->id }}">
                                                    </div>


                                                    <form action="{{ route('cart.reduce', ['product' => $product->id]) }}" method="post">
                                                        @csrf
                                                        <input type="submit" name="submit" id="{{ 'cart_submit_reduce_' . $product->id }}" value="submit" style="display: none">
                                                    </form>
                                                </td>
                                                <td class="pro-subtotal">
                                                    <span>${{ number_format(($product->price * (100 - $product->discount ?? 0) / 100) * $product->pivot->quantity, 2) }}</span>
                                                </td>
                                            @else
                                                <td colspan="3">
                                                    <span class="text-danger">Stock Out</span>
                                                </td>
                                            @endif
                                            <form action="{{ route('cart.remove', ['product' => $product->id]) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" name="submit" id="{{ 'cart_submit_delete_' . $product->id }}" value="submit" style="display: none">
                                            </form>
                                            <td class="pro-remove">
                                                <a href="javascript:clickSubmit('{{ 'cart_submit_delete_' . $product->id }}')">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <!-- Cart Update Option -->
                        <div class="cart-update-option d-block d-md-flex justify-content-between">
                            <div class="apply-coupon-wrapper">
                                @if($coupon)
                                    <form action="{{ route('cart.coupon.remove') }}" method="post" class=" d-block d-md-flex">
                                        @csrf
                                        @method('patch')
                                        <h5 style="margin-right: 30px">COUPON: ${{ number_format($coupon, 2) }}</h5>
                                        <button type="submit" name="submit" class="sqr-btn">Remove Coupon</button>
                                    </form>
                                @else
                                    <form action="{{ route('cart.coupon.apply') }}" method="post" class=" d-block d-md-flex">
                                        @csrf
                                        @method('patch')
                                        <input name="code" type="text" placeholder="Enter Your Coupon Code" required/>
                                        @error('code')
                                            <div class="error" style="margin-right: 10px">{{ $message }}</div>
                                        @enderror
                                        <button type="submit" name="submit" class="sqr-btn">Apply Coupon</button>
                                    </form>
                                @endif
                            </div>
                            <div class="cart-update mt-sm-16">
                                <a href="{{ route('product.index') }}" class="sqr-btn">Update Cart</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h3>Cart Totals</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Sub Total</td>
                                            <td>${{ number_format($subtotalPrice, 2) }}</td>
                                        </tr>
                                        @if($coupon)
                                            <tr>
                                                <td>Coupon</td>
                                                <td>${{ number_format($coupon, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Shipping</td>
                                            <td>${{ number_format($shipping, 2) }}</td>
                                        </tr>
                                        <tr class="total">
                                            <td>Total Amount</td>
                                            <td class="total-amount">${{ number_format($subtotalPrice + $shipping - $coupon, 2) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="{{ route('checkout') }}" class="sqr-btn d-block">Proceed To Checkout</a>
                        </div>
                    </div>
                </div>
            @else
                <center>
                    <h2>There isn't any product.</h2>
                </center>
                <br><br>
                <div class="cart-update mt-sm-16">
                    <center><a href="{{ route('product.index') }}" class="sqr-btn">Update Cart</a></center>
                </div>
                <br>
            @endif
        </div>
    </div>

@endsection


@section('script')

    <script>
        // quantity change js
        var proQty = $('.pro-qty');
        proQty.prepend('<span class="dec qtybtn">-</span>');
        proQty.append('<span class="inc qtybtn">+</span>');
        proQty.on('click', '.qtybtn', function () {
            var $button = $(this);
            var oldValue = $button.parent().find('input.quantity').val();
            var productId = $button.parent().find('input.product_id').val();
            if ($button.hasClass('inc')) {
                clickSubmit('cart_submit_add_' + productId)
            }
            else {
                // Don't allow decrementing below zero
                if (oldValue > 1) {
                    clickSubmit('cart_submit_reduce_' + productId)
                }
                else {
                    newVal = 1;
                }
            }
            $button.parent().find('input').val(newVal);
        });
    </script>

@endsection
