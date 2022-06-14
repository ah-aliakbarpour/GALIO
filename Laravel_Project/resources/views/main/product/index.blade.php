@extends('main.layouts.app')



@section('title', 'Product')



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
                                <li class="breadcrumb-item active" aria-current="page">Shop</li>
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

    <!-- page wrapper start -->
    <div class="page-main-wrapper">
        <div class="container">
            <div class="row">
                <!-- sidebar start -->
                <div class="col-lg-3 order-2 order-lg-1">
                    <div class="shop-sidebar-wrap mt-md-28 mt-sm-28">

                        @if(request()->input('submit'))
                            <div class="sidebar-widget mb-30">
                                <a href="{{ route('product.index') }}">
                                    <button class="btn">Reset Filters</button>
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('product.index') }}" method="get" id="filter_form">
                            <input type="submit" name="submit" id="filter_submit" value="Filter" style="display: none">
                            <input type="hidden" id="category" name="category" value="{{ request()->input('category') }}">
                            <input type="hidden" id="tag" name="tag" value="{{ request()->input('tag') }}">

                            <!-- sidebar categorie start -->
                            <div class="sidebar-widget mb-30">
                                <div class="sidebar-category">
                                    <ul>
                                        <li class="title"><i class="fa fa-bars"></i> categories</li>
                                        @foreach($categories as $category)
                                            <li><a @class(['active' => request()->input('category') == $category->id])
                                                   href="javascript:clickSubmit('filter_submit', 'category', {{ $category->id }})">{{ $category->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- sidebar categorie start -->

                            <div class="sidebar-widget custom-control custom-checkbox mb-20">
                                <input name="in_stock" type="checkbox" class="custom-control-input" id="terms" onclick="clickSubmit('filter_submit')"
                                       @if(request()->input('in_stock') == 'on') checked @endif>
                                <label class="custom-control-label" for="terms">Only in stock</label>
                            </div>

                            <!-- pricing filter start -->
                            <div class="sidebar-widget mb-30">
                                <div class="sidebar-title mb-10">
                                    <h3>filter by price</h3>
                                </div>
                                <div class="sidebar-widget-body">
                                    <div class="price-range-wrap">
                                        @php
                                            $inputPriceRange = explode(' - ', str_replace('$', '',
                                                request()->input('price_range') ?? '$' . $priceRange->min . ' - $' . $priceRange->max));
                                            $inputMinPrice = $inputPriceRange[0];
                                            $inputMaxPrice = $inputPriceRange[1];
                                        @endphp
                                        <div class="price-range" data-min="{{ $priceRange->min }}" data-max="{{ $priceRange->max }}"
                                             data-input_min="{{ $inputMinPrice }}" data-input_max="{{ $inputMaxPrice }}"></div>
                                        <div class="range-slider">
                                            <div class="d-flex justify-content-between">
                                                <button type="submit" name="submit" value="Submit" class="filter-btn">filter</button>
                                                <div class="price-input d-flex align-items-start">
                                                    <input name="price" type="text" id="amount">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- pricing filter end -->

                            <!-- product tag start -->
                            <div class="sidebar-widget mb-30">
                                <div class="sidebar-title mb-10">
                                    <h3>tags</h3>
                                </div>
                                <div class="sidebar-widget-body">
                                    <div class="product-tag">
                                        @foreach($tags as $tag)
                                            <a  @class(['active' => request()->input('tag') == $tag->id])
                                                href="javascript:clickSubmit('filter_submit', 'tag', {{ $tag->id }})">{{ $tag->title }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- product tag end -->
                        </form>
                    </div>
                </div>
                <!-- sidebar end -->

                <!-- product main wrap start -->
                <div class="col-lg-9 order-1 order-lg-2">
                    @if($products->isEmpty())
                        <center>
                            <h3>There isn't any product!</h3>
                        </center>
                    @else
                    <!-- product view wrapper area start -->
                        <div class="shop-product-wrapper">
                            <!-- shop product top wrap start -->
                            <div class="shop-top-bar">
                                <div class="row">
                                    <div class="col-lg-7 col-md-6">
                                        <div class="top-bar-left">
                                            <div class="product-amount">
                                                <p>Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} Of {{ $products->total() }} Results</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-6">
                                        <div class="top-bar-right">
                                            <div class="product-short">
                                                <p>Sort By : </p>
                                                <select form="filter_form" name="sort" class="nice-select" onchange="clickSubmit('filter_submit')">
                                                    <option value="created_at,desc" selected>Date (New - Old)</option>
                                                    <option value="created_at,asc" @if(request()->input('sort') == 'created_at,asc') selected @endif>Date (Old - New)</option>
                                                    <option value="title,asc" @if(request()->input('sort') == 'title,asc') selected @endif>Title (A - Z)</option>
                                                    <option value="title,desc" @if(request()->input('sort') == 'title,desc') selected @endif>Title (Z - A)</option>
                                                    <option value="price,asc" @if(request()->input('sort') == 'price,asc') selected @endif>Price (Law - High)</option>
                                                    <option value="price,desc" @if(request()->input('sort') == 'price,desc') selected @endif>Price (High - Law)</option>
                                                    <option value="discount,asc" @if(request()->input('sort') == 'discount,asc') selected @endif>Discount (Law - High)</option>
                                                    <option value="discount,desc" @if(request()->input('sort') == 'discount,desc') selected @endif>Discount (High - Law)</option>
                                                    <option value="rating,asc" @if(request()->input('sort') == 'rating,asc') selected @endif>Rating (Law - High)</option>
                                                    <option value="rating,desc" @if(request()->input('sort') == 'rating,desc') selected @endif>Rating (High - Law)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- shop product top wrap start -->

                            <!-- product item start -->
                            <div class="shop-product-wrap grid row">
                                @foreach($products as $product)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <!-- product single grid item start -->
                                        <div class="product-item fix mb-30">
                                            <div class="product-thumb" style="display: flex; justify-content: center;">
                                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    @if($product->images->isNotEmpty())
                                                        <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 195px; width: auto !important; object-fit: contain">
                                                    @else
                                                        <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 195px; width: auto !important; object-fit: contain">
                                                    @endif
                                                </a>
                                                @if($product->label())
                                                    <div class="product-label">
                                                        <span>{{ $product->label() }}</span>
                                                    </div>
                                                @endif
                                                <div class="product-action-link" style="padding-top: 60px">
                                                    <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="post">
                                                        @csrf
                                                        <input type="submit" name="submit" id="{{ 'wishlist_submit_' . $product->id }}" value="submit" style="display: none">
                                                    </form>
                                                    <a href="javascript:clickSubmit('{{ 'wishlist_submit_' . $product->id }}')"
                                                       data-toggle="tooltip" data-placement="left" title="Wishlist">
                                                        <i class="fa fa-heart-o"></i>
                                                    </a>
                                                    <form action="{{ route('cart.add', ['product' => $product->id]) }}" method="post">
                                                        @csrf
                                                        <input type="submit" name="submit" id="{{ 'cart_submit_' . $product->id }}" value="submit" style="display: none">
                                                    </form>
                                                    <a href="javascript:clickSubmit('{{ 'cart_submit_' . $product->id }}')"
                                                       data-toggle="tooltip" data-placement="left" title="Add to cart">
                                                        <i class="fa fa-shopping-cart"></i></a>
                                                </div>
                                            </div>
                                            <div class="product-content">
                                                <h4 title="{{ $product->title }}">
                                                    <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                                       style="height: 36px;
                                                            /* ellipsis text in two line <> */
                                                            display: -webkit-box;
                                                            -webkit-line-clamp: 2;
                                                            -webkit-box-orient: vertical;
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;
                                                            /* ellipsis text in two line </> */">
                                                        {{ $product->title }}
                                                    </a>
                                                </h4>
                                                <div class="pricebox">
                                                    @if($product->stock)
                                                        @if($product->discount)
                                                            <span class="regular-price">
                                                            ${{ number_format($product->price * (100 - $product->discount) / 100, 2) }}
                                                            </span>
                                                            <span class="old-price">
                                                                <del>${{ number_format($product->price, 2) }}</del>
                                                            </span>
                                                            <span style="color: #d8373e;">
                                                                %{{ $product->discount }}
                                                            </span>
                                                        @else
                                                            <span class="regular-price">
                                                                ${{ number_format($product->price, 2) }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <div class="availability mt-10">
                                                            <span style="color: #d8373e;">Stock Out</span>
                                                        </div>
                                                    @endif
                                                    <div class="ratings">
                                                        @if($product->reviewsNumber())
                                                            {{ $product->rating() }}
                                                            <span style="margin-left: 3px;"><i class="fa fa-star"></i></span>
                                                            <div class="pro-review">
                                                                <span>{{ $product->reviewsNumber() }} review(s)</span>
                                                            </div>
                                                        @else
                                                            <div class="pro-review">
                                                                <span>There isn't any review</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- product single grid item end -->
                                    </div>
                                @endforeach
                            </div>
                            <!-- product item end -->
                        </div>
                        <!-- product view wrapper area end -->

                        <!-- start pagination area -->
                        <center style="margin-inline: 10px">
                            {{ $products->links('main.layouts.pagination') }}
                        </center>
                        <!-- end pagination area -->
                    @endif

                </div>
                <!-- product main wrap end -->
            </div>
        </div>
    </div>
    <!-- page wrapper end -->

@endsection
