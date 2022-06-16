@extends('main.layouts.app')


@section('content')

    <br>

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

    <!-- hero slider start -->
    <div class="hero-slider-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="slider-wrapper-area2">
                        <div class="hero-slider-active hero__2 slick-dot-style hero-dot">
                            @foreach($banners as $banner)
                                <div class="single-slider d-flex align-items-center" style="background-image: url({{ $banner->imagePath() }});">
                                    <div class="container">
                                        <div class="slider-main-content">
                                            <div class="slider-text text-center">
                                                <h2>{{ $banner->first_header }}</h2>
                                                <h3>{{ $banner->second_header }}</h3>
                                                <p>{{ $banner->paragraph }}</p>
                                                <a class="home-btn" href="{{ $banner->link }}">shop now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- hero slider end -->

    <!-- home banner area start -->
    <div class="banner-area mt-30 pb-4 mt-xs-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-5 order-1">
                    @if($advertisements[1] != null)
                        <div class="img-container img-full fix">
                            <a href="{{ $advertisements[1]->link }}">
                                <img src="{{ $advertisements[1]->imagePath() }}">
                                {{-- style="height: 355px;" --}}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-lg-5 col-md-5 order-sm-3 mt-sm-30">
                    @if($advertisements[2] != null)
                        <div class="img-container img-full fix mb-30">
                            <a href="{{ $advertisements[2]->link }}">
                                <img src="{{ $advertisements[2]->imagePath() }}">
                                {{-- style="height: 162px;" --}}
                            </a>
                        </div>
                    @endif
                    @if($advertisements[3] != null)
                        <div class="img-container img-full fix mb-30">
                            <a href="{{ $advertisements[3]->link }}">
                                <img src="{{ $advertisements[3]->imagePath() }}">
                                {{-- style="height: 162px;" --}}
                            </a>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-md-4 col-sm-7 order-2 order-md-3 mt-xs-30">
                    @if($advertisements[4] != null)
                        <div class="img-container img-full fix">
                            <a href="{{ $advertisements[4]->link }}">
                                <img src="{{ $advertisements[4]->imagePath() }}">
                                {{-- style="height: 355px;" --}}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- home banner area end -->

    @if($mostRatedProducts->isNotEmpty())
        <!-- most rated products start -->
        <div class="latest-product latest-pro-2 pt-lg-0 pt-md-0 pt-sm-0 mt-xs-28">
            <div class="container">
                <div class="section-title mb-30">
                    <div class="title-icon">
                        <i class="fa fa-star"></i>
                    </div>
                    <h3>Most rated products</h3>
                </div> <!-- section title end -->
                <!-- featured category start -->
                <div class="latest-product-active slick-padding slick-arrow-style">
                    @foreach($mostRatedProducts as $product)
                        <div class="product-item fix">
                            <div class="product-thumb">
                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 195px; width: auto !important; object-fit: contain" class="img-pri">
                                    @else
                                        <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 195px; width: auto !important; object-fit: contain" class="img-pri">
                                    @endif
                                </a>
                                <div class="product-label">
                                    <span>top</span>
                                </div>
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
                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                    <h4 title="{{ $product->title }}"
                                        style="height: 36px;
                                                                /* ellipsis text in two line <> */
                                                                display: -webkit-box;
                                                                -webkit-line-clamp: 2;
                                                                -webkit-box-orient: vertical;
                                                                overflow: hidden;
                                                                text-overflow: ellipsis;
                                                                /* ellipsis text in two line </> */">
                                        {{ $product->title }}
                                    </h4>
                                </a>
                                <div class="pricebox">
                                    @if($product->discount)
                                        <span class="regular-price">
                                            ${{ number_format($product->price * (100 - $product->discount) / 100, 2) }}
                                        </span>
                                        <span class="old-price">
                                            <del>${{ number_format($product->price, 2) }}</del>
                                        </span>
                                    @else
                                        <span class="regular-price">
                                            ${{ number_format($product->price, 2) }}
                                        </span>
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
                    @endforeach
                </div>
                <!-- featured category end -->
            </div>
        </div>
        <!-- most rated product end -->
    @endif

    <!-- page wrapper start -->
    <div class="main-home-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @if($discountedProducts->isNotEmpty())
                        <div class="main-sidebar category-wrapper mb-10 pt-20 mt-md-8">
                            <div class="section-title-2 d-flex justify-content-between mb-28">
                                <h3>discounts</h3>
                                <div class="category-append"></div>
                            </div> <!-- section title end -->
                            <div class="category-carousel-active row" data-row="3">
                                @foreach($discountedProducts as $product)
                                    <div class="col">
                                        <div class="category-item">
                                            <div class="category-thumb">
                                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                    @if($product->images->isNotEmpty())
                                                        <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 88px; width: 88px; object-fit: contain">
                                                    @else
                                                        <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 88px; width: 88px; object-fit: contain">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="category-content">
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
                                                <div class="price-box">
                                                    <div class="regular-price">
                                                        ${{ number_format($product->price * (100 - $product->discount) / 100, 2) }}
                                                    </div>
                                                    <div class="old-price">
                                                        <del>${{ number_format($product->price, 2) }}</del>
                                                    </div>
                                                </div>
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
                                @endforeach
                            </div>
                        </div>
                        <!-- discounts area end -->
                    @endif
                    <!-- sidebar banner start -->
                    @if($advertisements[6] != null)
                        <div class="sidebar-widget mb-22">
                            <div class="img-container fix img-full mt-30">
                                <a href="{{ $advertisements[6]->link }}">
                                    <img src="{{ $advertisements[6]->imagePath() }}">
                                </a>
                                {{-- style="height: 440px;" --}}
                            </div>
                        </div>
                    @endif
                    <!-- sidebar banner end -->
                </div>
                <div class="col-lg-9">
                    <!-- banner statistic start -->
                    @if($advertisements[5] != null)
                        <div class="banner-statistic pt-22">
                            <div class="img-container fix img-full">
                                <a href="{{ $advertisements[5]->link }}">
                                    <img src="{{ $advertisements[5]->imagePath() }}">
                                    {{-- style="height: 197px;" --}}
                                </a>
                            </div>
                        </div>
                    @endif
                    <!-- banner statistic end -->
                    @if($featuredProducts->isNotEmpty())
                        <!-- featured category area start -->
                        <div class="feature-category-area mt-22">
                            <div class="section-title mb-30">
                                <div class="title-icon">
                                    <i class="fa fa-bookmark"></i>
                                </div>
                                <h3>featured</h3>
                            </div> <!-- section title end -->
                            <!-- featured category start -->
                            <div class="container">
                                <div class="featured-carousel-active2 row slick-arrow-style" data-row="2">
                                    @foreach($featuredProducts as $product)
                                        <div class="col">
                                            <div class="product-item fix mb-30">
                                                <div class="product-thumb">
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
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- featured category end -->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- page wrapper end -->

    @if($newProducts->isNotEmpty())
        <!-- new products start -->
        <div class="latest-product latest-pro-2 pt-14 pt-lg-0 pt-md-0 pt-sm-0">
            <div class="container">
                <div class="section-title mb-30">
                    <div class="title-icon">
                        <i class="fa fa-flash"></i>
                    </div>
                    <h3>New products</h3>
                </div> <!-- section title end -->
                <!-- featured category start -->
                <div class="latest-product-active slick-padding slick-arrow-style">
                    @foreach($newProducts as $product)
                        <div class="product-item fix">
                            <div class="product-thumb">
                                <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ $product->imagePath($product->images->first()) }}"style="height: 210px; width: 210px; object-fit: contain" class="img-pri">
                                        {{-- style="height: 195px; width: auto !important; object-fit: contain" --}}
                                    @else
                                        <img src="{{ asset('storage/images/no_image_available/1.jpg') }}"style="height: 210px; width: 210px; object-fit: contain" class="img-pri">
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
                    @endforeach
                </div>
                <!-- featured category end -->
            </div>
        </div>
        <!-- new product end -->
    @endif

    @if($latestBlogs->isNotEmpty())
        <div class="latest-blog-area pt-28 pb-30">
            <div class="container">
                <div class="section-title mb-30">
                    <div class="title-icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <h3>latest blogs</h3>
                </div> <!-- section title end -->
                <!-- blog wrapper start -->
                <div class="blog-carousel-active2 slick-padding slick-arrow-style">
                    @foreach($latestBlogs as $blog)
                        <div class="blog-item">
                            <div class="blog-thumb img-full fix">
                                <a href="{{ route('blog.show', ['blog' => $blog->id]) }}">
                                    @if($blog->images->isNotEmpty())
                                        <img src="{{ $blog->imagePath($blog->images->first()) }}" style="height: 160px; object-fit: cover">
                                        {{--  style="max-height: 160px" --}}
                                    @else
                                        <img src="{{ asset('storage/images/no_image_available/6.jpg') }}" style="height: 160px; object-fit: cover">
                                        {{--  style="max-height: 160px" --}}
                                    @endif
                                </a>
                            </div>
                            <div class="blog-content">
                                <h3>
                                    <a href="{{ route('blog.show', ['blog' => $blog->id]) }}" title="{{ $blog->title }}"
                                       style="display: -webkit-box;
                                            -webkit-line-clamp: 1;
                                            -webkit-box-orient: vertical;
                                            overflow: hidden;
                                            text-overflow: ellipsis;">
                                        {{ $blog->title }}
                                    </a>
                                </h3>
                                <div class="blog-meta">
                                    <span class="posted-author">by: {{ $blog->author }}</span>
                                    <span class="post-date">{{ $blog->created_at->format('d M Y') }}</span>
                                </div>
                                <p style="text-align: justify">{{ \Illuminate\Support\Str::limit($blog->context, 105, '...') }}</p>
                            </div>
                            <a href="{{ route('blog.show', ['blog' => $blog->id]) }}">read more <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    @endforeach
                </div>
                <!-- blog wrapper end -->
            </div>
        </div>
    @endif


@endsection
