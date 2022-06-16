@extends('main.layouts.app')



@section('title', 'Product Details')


@section('style')

    <style>
        .img_container-1 {
            /*border: 1px solid red !important;*/
            position: relative !important;
            height: 400px !important;
        }
        .img-1 {
            position: absolute !important;
            margin: auto !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            max-height: 400px !important;
            width: auto !important;
        }

        .img_container-2 {
            position: relative !important;
            height: 100px !important;
        }
        .img-2 {
            position: absolute !important;
            margin: auto !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            max-height: 100px !important;
            width: auto !important;
        }

        .product-label-show {
            top: 0;
            left: 0;
            /*position: absolute;*/
            margin-bottom: 8px;
        }
        .product-label-show span {
            width: 50px;
            height: 25px;
            font-size: 14px;
            color: #ffffff;
            font-weight: 600;
            text-align: center;
            line-height: 25px;
            display: block;
            background-color: #f9af51;
            text-transform: uppercase;
        }
    </style>

@endsection


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
                                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Product Details</li>
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

    <!-- product details wrapper start -->
    <div class="product-details-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <!-- product details inner end -->
                    <div class="product-details-inner">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product-large-slider mb-20 slick-arrow-style_2">
                                    @php($cnt = 0)
                                    @while($product->images->isNotEmpty() && $cnt <= 4)
                                        @foreach($product->images as $image)
                                            <div class="pro-large-img img-zoom img_container-1">
                                                <img class="img-1" src="{{ $product->imagePath($image) }}"/>
                                            </div>
                                            @php($cnt++)
                                        @endforeach
                                    @endwhile
                                </div>
                                <div class="pro-nav slick-padding2 slick-arrow-style_2">
                                    @php($cnt = 0)
                                    @while($product->images->isNotEmpty() && $cnt <= 4)
                                        @foreach($product->images as $image)
                                            <div class="pro-nav-thumb img_container-2">
                                                <img class="img-2" src="{{ $product->imagePath($image) }}"/>
                                            </div>
                                            @php($cnt++)
                                        @endforeach
                                    @endwhile
                                 </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="product-details-des mt-md-34 mt-sm-34">
                                    <h3  style="text-transform: none !important; text-align: justify"><a>{{ $product->title }}</a></h3>
                                    @if($product->label())
                                        <div class="product-label-show">
                                            <span>{{ $product->label() }}</span>
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
                                    <div class="availability mt-10">
                                        <h5>Availability:</h5>
                                        @if($product->stock)
                                            <span>In Stock</span>
                                        @else
                                            <span style="color: #d8373e;">Stock Out</span>
                                        @endif
                                    </div>
                                    @if($product->stock)
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
                                        </div>
                                    @endif
                                    <p style="text-align: justify">{!! $product->introduction !!}</p>
                                    <div class="quantity-cart-box d-flex align-items-center">
                                        <div class="row" style="margin-left: 0">
                                            @if($product->stock)
                                                <div class="quantity mt-3">
                                                    <div class="pro-qty"><input name="quantity" type="text" value="1"></div>
                                                </div>
                                                <div class="action_link mr-3 mt-3">
                                                    <a class="buy-btn" href="javascript:clickSubmit('add_to_cart')">add to cart<i class="fa fa-shopping-cart"></i></a>
                                                    <form action="" method="post">
                                                        @csrf
                                                        <input type="submit" id="add_to_cart" name="submit" value="add_to_cart" style="display: none">
                                                    </form>
                                                </div>
                                            @endif
                                            <div class="useful-links mt-3">
                                                <a href="javascript:clickSubmit('add_to_wishlist')" data-toggle="tooltip" data-placement="top"
                                                ><i class="fa fa-heart-o"></i>add to wishlist</a>
                                                <form action="" method="post">
                                                    @csrf
                                                    <input type="submit" id="add_to_wishlist" name="submit" value="add_to_wishlist" style="display: none">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product details inner end -->

                    <!-- product details reviews start -->
                    <div class="product-details-reviews mt-34">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-review-info">
                                    <ul class="nav review-tab">
                                        <li>
                                            <a @class(['active' => !$errors->any()]) data-toggle="tab" href="#description">Description</a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#information">Information</a>
                                        </li>
                                        <li>
                                            <a @class(['active' => $errors->any()]) data-toggle="tab" href="#review">Reviews</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content reviews-tab">
                                        <div @class(['tab-pane', 'fade', 'show', 'active' => !$errors->any()]) id="description">
                                            <div class="tab-one" style="text-align: justify">
                                                {!! $product->description ?? '<h5>There isn\'t any description</h5>' !!}
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show" id="information">
                                            @if($product->information->isEmpty())
                                                <h5>There isn't any information</h5>
                                            @else
                                                <table class="table table-bordered">
                                                    <tbody>
                                                    @foreach($product->information as $information)
                                                        <tr>
                                                            <td>{{ $information->key }}</td>
                                                            <td>{{ $information->value }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                        <div @class(['tab-pane', 'fade', 'review-form', 'active' => $errors->any()]) id="review">
                                            @if($reviews->isEmpty())
                                                <h5>There isn't any review</h5>
                                            @else
                                                <h5 style="text-transform: none !important;">{{ $product->reviewsNumber() }} Review(s)</h5>
                                                @foreach($reviews as $review)
                                                    <div class="total-reviews">
                                                        <div class="rev-avatar">
                                                            <img src="{{ asset('template_main/assets/img/about/avatar.jpg') }}" alt="">
                                                        </div>
                                                        <div class="review-box">
                                                            <div class="ratings">
                                                                {{ $review->rating }} <span style="margin-left: 3px;"><i class="fa fa-star"></i></span>
                                                            </div>
                                                            <div class="post-author">
                                                                <p><span>{{ $review->author_name }} - </span>{{ $review->created_at->format('d M Y') }}</p>
                                                            </div>
                                                            <p style="text-align: justify">{{ $review->context }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <h4>leave a review:</h4>
                                            <form action="{{ route('product.review.store', ['product' => $product->id]) }}" method="post" id="create_review">
                                                @csrf
                                                <div class="form-group row">
                                                    <div @class([
                                                        'col',
                                                        'error' => $errors->has('name'),
                                                    ])>
                                                        <label class="col-form-label">Your Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <span>{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div @class([
                                                        'col',
                                                        'error' => $errors->has('email'),
                                                    ])>
                                                        <label class="col-form-label">Your Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                                        @error('email')
                                                            <span>{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div @class([
                                                        'col',
                                                        'error' => $errors->has('context'),
                                                    ])>
                                                        <label class="col-form-label">Your Review <span class="text-danger">*</span></label>
                                                        <textarea class="form-control" name="context" rows="3" required>{{ old('context') }}</textarea>
                                                        @error('context')
                                                            <span>{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div @class([
                                                        'col',
                                                        'error' => $errors->has('rating'),
                                                    ])>
                                                        <label class="col-form-label">Rating <span class="text-danger">*</span></label>
                                                        &nbsp;
                                                        <label for="1">1</label>
                                                        <input type="radio" id="1" value="1" name="rating" @if(old('rating', 5) == 1) checked @endif>
                                                        &nbsp;
                                                        <label for="2">2</label>
                                                        <input type="radio" id="2" value="2" name="rating" @if(old('rating', 5) == 2) checked @endif>
                                                        &nbsp;
                                                        <label for="3">3</label>
                                                        <input type="radio" id="3" value="3" name="rating" @if(old('rating', 5) == 3) checked @endif>
                                                        &nbsp;
                                                        <label for="4">4</label>
                                                        <input type="radio" id="4" value="4" name="rating" @if(old('rating', 5) == 4) checked @endif>
                                                        &nbsp;
                                                        <label for="5">5</label>
                                                        <input type="radio" id="5" value="5" name="rating" @if(old('rating', 5) == 5) checked @endif>
                                                        <br>
                                                        @error('rating')
                                                            <span>{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="buttons">
                                                    <input type="submit" name="submit" class="sqr-btn" value="Submit">
                                                </div>
                                            </form> <!-- end of review-form -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- product details reviews end -->

                    <!-- related products area start -->


                    @if($relatedProducts->isNotEmpty())
                        <div class="related-products-area mt-34">
                            <div class="section-title mb-30">
                                <div class="title-icon">
                                    <i class="fa fa-desktop"></i>
                                </div>
                                <h3>related products</h3>
                            </div> <!-- section title end -->
                            <!-- featured category start -->
                            <div class="featured-carousel-active slick-padding slick-arrow-style">
                                @foreach($relatedProducts as $product)
                                    <div class="product-item fix">
                                        <div class="product-thumb">
                                            <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                @if($product->images->isNotEmpty())
                                                    <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 210px; width: 210px; object-fit: contain" class="img-pri">
                                                @else
                                                    <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 210px; width: 210px; object-fit: contain" class="img-pri">
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
                    @endif
                    <!-- related products area end -->
                </div>

                <!-- sidebar start -->

                <div class="col-lg-3">
                    <div class="shop-sidebar-wrap fix mt-md-22 mt-sm-22">
                        @if($newProducts->isNotEmpty())
                            <!-- featured category start -->
                            <div class="sidebar-widget mb-22">
                                <div class="section-title-2 d-flex justify-content-between mb-28">
                                    <h3>New Products</h3>
                                    <div class="category-append"></div>
                                </div> <!-- section title end -->
                                <div class="category-carousel-active row" data-row="4">
                                    @foreach($newProducts as $product)
                                        <div class="col">
                                            <div class="category-item">
                                                <div class="category-thumb">
                                                    <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                        @if($product->images->isNotEmpty())
                                                            <img src="{{ $product->imagePath($product->images->first()) }}" style="height: 88px;width: 88px; object-fit: contain">
                                                        @else
                                                            <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="height: 88px;width: 88px; object-fit: contain">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="category-content">
                                                    <h4 title="{{ $product->title }}">
                                                        <a href="{{ route('product.show', ['product' => $product->id]) }}"
                                                           style="height: 36px;
                                                            /* ellipsis text in two line <> */
                                                            display: -webkit-box;
                                                            -webkit-line-clamp: 1;
                                                            -webkit-box-orient: vertical;
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;
                                                            /* ellipsis text in two line </> */">
                                                            {{ $product->title }}
                                                        </a>
                                                    </h4>
                                                    <div class="price-box">
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
                        @endif
                        <form action="{{ route('product.index') }}" method="get" id="filter_form">
                            <input type="submit" name="submit" id="filter_submit" value="Filter" style="display: none">
                            <input type="hidden" id="tag" name="tag">

                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-30">Tags</h4>
                                <ul class="blog-tags">
                                    @foreach($tags as $tag)
                                        <li><a href="javascript:clickSubmit('filter_submit', 'tag', {{ $tag->id }})">{{ $tag->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </form>

                        @if($advertisement != null)
                            <div class="sidebar-widget mb-22">
                                <div class="img-container fix img-full mt-30">
                                    <a href="{{ $advertisement->link }}">
                                        <img src="{{ $advertisement->imagePath() }}">
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- sidebar end -->
            </div>
        </div>
    </div>
    <!-- product details wrapper end -->

@endsection


@section('script')

    // If there is any validation error in review form
    @if($errors->any())
        <script>
            document.getElementById("create_review").scrollIntoView();
        </script>
    @endif

@endsection
