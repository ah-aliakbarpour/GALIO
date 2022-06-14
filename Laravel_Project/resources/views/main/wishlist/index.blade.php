@extends('main.layouts.app')



@section('title', 'Wishlist')



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
                                <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
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

    <div class="wishlist-main-wrapper">
        <div class="container">
            <!-- Wishlist Page Content Start -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- Wishlist Table Area -->
                    <div class="cart-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="pro-thumbnail">Image</th>
                                    <th class="pro-title">Product</th>
                                    <th class="pro-price">Price</th>
                                    <th class="pro-quantity">Stock Status</th>
                                    <th class="pro-subtotal">Add to Cart</th>
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
                                        <td class="pro-price">
                                            @if($product->stock)
                                                <span>${{ number_format($product->price * (100 - $product->discount ?? 0) / 100, 2) }}</span>
                                            @else
                                                <span>&#9866;</span>
                                            @endif
                                        </td>
                                        <td class="pro-quantity">
                                            @if($product->stock)
                                                <span class="text-success">In Stock</span>
                                            @else
                                                <span class="text-danger">Stock Out</span>
                                            @endif
                                        </td>
                                        <td class="pro-subtotal"><a href="cart.html" class="sqr-btn text-white">Add to Cart</a></td>
                                        <form action="{{ route('wishlist.remove', ['product' => $product->id]) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" name="submit" id="{{ 'wishlist_submit_' . $product->id }}" value="submit" style="display: none">
                                        </form>
                                        <td class="pro-remove">
                                            <a href="javascript:clickSubmit('{{ 'wishlist_submit_' . $product->id }}')">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Wishlist Page Content End -->
        </div>
    </div>

@endsection
