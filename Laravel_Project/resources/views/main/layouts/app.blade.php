<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="meta description">

    <!-- Site title -->
    <title>GALIO
        @if(\Illuminate\Support\Facades\Route::currentRouteName() != 'index') | @endif
        @yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('template_main/assets/img/favicon.ico') }}" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('template_main/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font-Awesome CSS -->
    <link href="{{ asset('template_main/assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- helper class css -->
    <link href="{{ asset('template_main/assets/css/helper.min.css') }}" rel="stylesheet">
    <!-- Plugins CSS -->
    <link href="{{ asset('template_main/assets/css/plugins.css') }}" rel="stylesheet">
    <!-- Main Style CSS -->
    <link href="{{ asset('template_main/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('template_main/assets/css/skin-default.css') }}" rel="stylesheet" id="galio-skin">


    <style>
        .error {
            color: #d8373e;
        }
        .error .form-control {
            border-color: #d8373e;
        }

        .btn {
            border: none;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            line-height: 36px;
            padding: 0 15px;
            background-color: #444444;

            border-radius: 0;
        }

        .btn:hover {
            background-color: #d8373e;
        }
    </style>

    @yield('style')
</head>
<body>

    @php
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Route;

        $routeName = explode('.', Route::currentRouteName());

        if (Auth::check())
        {
            $cartProducts = Auth::user()->cart->products();

            $cartProductsNumber = $cartProducts->count();
            $cartProducts = $cartProducts->orderBy('cart_product.created_at', 'asc')->limit(5)->get();

            $subtotalPrice = 0;

            foreach ($cartProducts as $product)
                $subtotalPrice += ($product->price * (100 - $product->discount ?? 0) / 100) * $product->pivot->quantity;
        }

    @endphp

    <div class="wrapper box-layout">

        <!-- header area start -->
        <header>

            <!-- header top start -->
            <div class="header-top-area bg-gray text-center text-md-left">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-5">
                            <div class="header-call-action">
                                <a>
                                    <i class="fa fa-envelope"></i>
                                    ah.aliakbarpour@gmail.com
                                </a>
                                <a>
                                    <i class="fa fa-phone"></i>
                                    09188284088
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-7">
                            <div class="header-top-right float-md-right float-none">
                                <nav>
                                    <ul>
                                        @auth
                                            <li>
                                                <div class="dropdown header-top-dropdown">
                                                    <a class="dropdown-toggle" id="myaccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ \Illuminate\Support\Facades\Auth::user()->email }}
                                                        <i class="fa fa-angle-down"></i>
                                                    </a>
                                                    <div class="dropdown-menu" aria-labelledby="myaccount">
                                                        <form action="{{ route('logout') }}" method="post">
                                                            @csrf
                                                            <input type="submit" id="logout_submit" name="submit" value="Logout" style="display: none">
                                                        </form>
                                                        <a class="dropdown-item" href="javascript:clickSubmit('logout_submit')">Logout</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="{{ route('wishlist.index') }}">my wishlist</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('cart.index') }}">my cart</a>
                                            </li>
                                        @else
                                            <li>
                                                <a href="{{ route('register') }}">Register</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('login') }}">Login</a>
                                            </li>
                                        @endauth
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header top end -->

            <!-- header middle start -->
            <div class="header-middle-area pt-20 pb-20">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <div class="brand-logo">
                                <a href="{{ route('index') }}">
                                    <img src="{{ asset('template_main/assets/img/logo/logo.png') }}" alt="brand logo">
                                </a>
                            </div>
                        </div> <!-- end logo area -->
                        <div class="col-lg-9">
                            <div class="header-middle-right">
                                <div class="header-middle-shipping mb-20">
                                    <div class="single-block-shipping">
                                        <div class="shipping-icon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                        <div class="shipping-content">
                                            <h5>Working time</h5>
                                            <span>Mon- Sun: 8.00 - 18.00</span>
                                        </div>
                                    </div> <!-- end single shipping -->
                                    <div class="single-block-shipping">
                                        <div class="shipping-icon">
                                            <i class="fa fa-truck"></i>
                                        </div>
                                        <div class="shipping-content">
                                            <h5>free shipping</h5>
                                            <span>On order over $199</span>
                                        </div>
                                    </div> <!-- end single shipping -->
                                    <div class="single-block-shipping">
                                        <div class="shipping-icon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <div class="shipping-content">
                                            <h5>money back 100%</h5>
                                            <span>Within 30 Days after delivery</span>
                                        </div>
                                    </div> <!-- end single shipping -->
                                </div>
                                <div class="header-middle-block">
                                    <div class="header-middle-searchbox">
                                        <form action="{{ route('product.index') }}" method="get">
                                            <input name="search_title" type="text" placeholder="Search..." required>
                                            <button type="submit" name="submit" value="Search" class="search-btn"><i class="fa fa-search"></i></button>
                                        </form>
                                    </div>
                                    <div class="header-mini-cart">
                                        <div class="mini-cart-btn mini-cart-dropdown-toggle">
                                            <i class="fa fa-shopping-cart"></i>
                                            @auth
                                                @if($cartProductsNumber)
                                                    <span class="cart-notification">{{ $cartProductsNumber }}</span>
                                                @endif
                                            @endauth
                                        </div>
                                        <div class="cart-total-price mini-cart-dropdown-toggle">
                                            <span>total</span>
                                            @auth
                                                ${{ number_format($subtotalPrice, 2) }}
                                            @else
                                                 $0
                                            @endauth
                                        </div>
                                        <ul class="cart-list">
                                            @forelse($cartProducts ?? [] as $product)
                                                <li>
                                                    <div class="cart-img">
                                                        <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                            @if($product->images->isNotEmpty())
                                                                <img src="{{ $product->imagePath($product->images->first()) }}">
                                                                {{-- style="height: 58px; width: auto !important; object-fit: contain" --}}
                                                            @else
                                                                <img src="{{ asset('storage/images/no_image_available/1.jpg') }}">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="cart-info">
                                                        <h4>
                                                            <a href="{{ route('product.show', ['product' => $product->id]) }}">
                                                                {{ \Illuminate\Support\Str::limit($product->title, 50   , '...') }}
                                                            </a>
                                                        </h4>
                                                        @if($product->stock)
                                                            <span>${{ number_format($product->price * (100 - $product->discount ?? 0) / 100, 2) }}</span>
                                                        @else
                                                            <span class="text-danger">Stock Out</span>
                                                        @endif
                                                    </div>
                                                    <form action="{{ route('cart.remove', ['product' => $product->id]) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <input type="submit" name="submit" id="{{ 'cart_submit_delete_' . $product->id }}" value="submit" style="display: none">
                                                    </form>
                                                    <div class="del-icon" onclick="javascript:clickSubmit('{{ 'cart_submit_delete_' . $product->id }}')">
                                                        <i class="fa fa-times"></i>
                                                    </div>
                                                </li>
                                            @empty
                                                <li>There isn't any product.</li>
                                            @endforelse
                                            <li class="mini-cart-price">
                                                <span class="subtotal">subtotal : </span>
                                                <span class="subtotal-price">
                                                    @auth
                                                        ${{ number_format($subtotalPrice, 2) }}
                                                    @else
                                                        $0
                                                    @endauth
                                                </span>
                                            </li>
                                            <li class="checkout-btn">
                                                <a href="{{ route('cart.index') }}">Open Cart</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- header middle end -->

            <!-- main menu area start -->
            <div class="main-header-wrapper bdr-bottom1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-header-inner">
                                <div class="category-toggle-wrap">
                                    <div class="category-toggle">
                                        category
                                        <div class="cat-icon">
                                            <i class="fa fa-angle-down"></i>
                                        </div>
                                    </div>
                                    <nav class="category-menu category-style-2">
                                        <ul>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-desktop"></i> computer</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-clock-o"></i> watch</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-television"></i> electronic</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-tablet"></i> tablet</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-book"></i> books</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-microchip"></i> microchip</a></li>
                                            <li><a href="shop-grid-left-sidebar.html"><i class="fa fa-bullhorn"></i> bullhorn</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="main-menu">
                                    <nav id="mobile-menu">
                                        <ul>
                                            <li  @class([
                                                'static',
                                                'active' => ($routeName[0] ?? '') == 'index',
                                            ])>
                                                <a href="{{ route('index') }}"><i class="fa fa-home"></i>Home</a>
                                            </li>
                                            <li  @class([
                                                'static',
                                                'active' => ($routeName[0] ?? '') == 'product',
                                            ])>
                                                <a href="{{ route('product.index') }}">Shop</a>
                                            </li>
                                            <li  @class([
                                                'static',
                                                'active' => ($routeName[0] ?? '') == 'blog',
                                            ])>
                                                <a href="{{ route('blog.index') }}">Blog</a>
                                            </li>
                                            <li  @class([
                                                'static',
                                                'active' => ($routeName[0] ?? '') == 'about-us',
                                            ])>
                                                <a href="{{ route('about-us') }}">About Us</a>
                                            </li>
                                            <li  @class([
                                                'static',
                                                'active' => ($routeName[0] ?? '') == 'contact-us',
                                            ])>
                                                <a href="{{ route('contact-us') }}">Contact Us</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-block d-lg-none"><div class="mobile-menu"></div></div>
                    </div>
                </div>
            </div>
            <!-- main menu area end -->

        </header>
        <!-- header area end -->








        @yield('content')








        <!-- footer area start -->
        <footer class="mt-36">
            <!-- footer top start -->
            <div class="footer-top bg-black pb-14 pt-14">
                <div class="container">
                    <div class="footer-top-wrapper">
                        <div class="newsletter__wrap">
                            <div class="newsletter__title">
                                <div class="newsletter__icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="newsletter__content">
                                    <h3>sign up for newsletter</h3>
                                    <p>Duis autem vel eum iriureDuis autem vel eum</p>
                                </div>
                            </div>
                            <div class="newsletter__box">
                                <form action="{{ route('subscribe') }}" method="post">
                                    @csrf
                                    <input type="email" name="email" placeholder="Email" required>
                                    <button type="submit" name="submit">subscribe!</button>
                                </form>
                            </div>
                            <!-- mailchimp-alerts Start -->
                            <div class="mailchimp-alerts">
                                <div class="mailchimp-submitting"></div><!-- mailchimp-submitting end -->
                                <div class="mailchimp-success"></div><!-- mailchimp-success end -->
                                <div class="mailchimp-error"></div><!-- mailchimp-error end -->
                            </div>
                            <!-- mailchimp-alerts end -->
                        </div>
                        <div class="social-icons">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Twitter"><i class="fa fa-twitter"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Instagram"><i class="fa fa-instagram"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Google-plus"><i class="fa fa-google-plus"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Youtube"><i class="fa fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- footer top end -->

            <!-- footer main start -->
            <div class="footer-widget-area pt-40 pb-38 pb-sm-4 pt-sm-30">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="footer-widget mb-sm-26">
                                <div class="widget-title mb-10 mb-sm-6">
                                    <h4>contact us</h4>
                                </div>
                                <div class="widget-body">
                                    <ul class="location">
                                        <li><i class="fa fa-envelope"></i>ah.aliakbarpour@gmial.com</li>
                                        <li><i class="fa fa-phone"></i>09188284088</li>
                                        <li><i class="fa fa-map-marker"></i>Address: Iran, Tehran</li>
                                    </ul>
                                </div>
                            </div> <!-- single widget end -->
                        </div> <!-- single widget column end -->
                        <div class="col-md-3 col-sm-6">
                            <div class="footer-widget mb-sm-26">
                                <div class="widget-title mb-10 mb-sm-6">
                                    <h4>my account</h4>
                                </div>
                                <div class="widget-body">
                                    <ul>
                                        <li><a href="#">my account</a></li>
                                        <li><a href="#">my cart</a></li>
                                        <li><a href="#">checkout</a></li>
                                        <li><a href="#">my wishlist</a></li>
                                        <li><a href="#">login</a></li>
                                    </ul>
                                </div>
                            </div> <!-- single widget end -->
                        </div> <!-- single widget column end -->
                        <div class="col-md-3 col-sm-6">
                            <div class="footer-widget mb-sm-26">
                                <div class="widget-title mb-10 mb-sm-6">
                                    <h4>short code</h4>
                                </div>
                                <div class="widget-body">
                                    <ul>
                                        <li><a href="#">gallery</a></li>
                                        <li><a href="#">accordion</a></li>
                                        <li><a href="#">carousel</a></li>
                                        <li><a href="#">map</a></li>
                                        <li><a href="#">tab</a></li>
                                    </ul>
                                </div>
                            </div> <!-- single widget end -->
                        </div> <!-- single widget column end -->
                        <div class="col-md-3 col-sm-6">
                            <div class="footer-widget mb-sm-26">
                                <div class="widget-title mb-10 mb-sm-6">
                                    <h4>product tags</h4>
                                </div>
                                <div class="widget-body">
                                    <ul>
                                        <li><a href="#">computer</a></li>
                                        <li><a href="#">camera</a></li>
                                        <li><a href="#">smart phone</a></li>
                                        <li><a href="#">watch</a></li>
                                        <li><a href="#">tablet</a></li>
                                    </ul>
                                </div>
                            </div> <!-- single widget end -->
                        </div> <!-- single widget column end -->
                    </div>
                </div>
            </div>
            <!-- footer main end -->

        </footer>
        <!-- footer area end -->

    </div>


    <!-- Scroll to top start -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>
    <!-- Scroll to Top End -->


    <!--All jQuery, Third Party Plugins & Activation (main.js) Files-->
    <script src="{{ asset('template_main/assets/js/vendor/modernizr-3.6.0.min.js') }}"></script>
    <!-- Jquery Min Js -->
    <script src="{{ asset('template_main/assets/js/vendor/jquery-3.3.1.min.js') }}"></script>
    <!-- Popper Min Js -->
    <script src="{{ asset('template_main/assets/js/vendor/popper.min.js') }}"></script>
    <!-- Bootstrap Min Js -->
    <script src="{{ asset('template_main/assets/js/vendor/bootstrap.min.js') }}"></script>
    <!-- Plugins Js-->
    <script src="{{ asset('template_main/assets/js/plugins.js') }}"></script>
    <!-- Ajax Mail Js -->
    <script src="{{ asset('template_main/assets/js/ajax-mail.js') }}"></script>
    <!-- Active Js -->
    <script src="{{ asset('template_main/assets/js/main.js') }}"></script>



    // Click submit by clicking a link (a tag)
    <script>
        function clickSubmit(id, input=null, value=null)
        {
            if (input != null)
                document.getElementById(input).value = value;

            document.getElementById(id).click();
        }
    </script>


    @yield('script')

</body>
</html>
