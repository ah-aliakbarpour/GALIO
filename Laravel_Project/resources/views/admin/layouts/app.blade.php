<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>GALIO-Admin | @yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('template_admin/dist/icon/favicon.ico') }}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('template_admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template_admin/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template_admin/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('template_admin/plugins/iCheck/flat/flat.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template_admin/dist/css/AdminLTE.min.css') }}">
    <!-- Blue Skin -->
    <link rel="stylesheet" href="{{ asset('template_admin/dist/css/skins/skin-blue.min.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


    @yield('style')

</head>
<body class="hold-transition skin-blue sidebar-mini">
    @php
        use App\Models\Comment;
        use App\Models\Order;
        use App\Models\Review;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\Route;


        $routeName = explode('.', Route::currentRouteName());


        $user = Auth::user();


        $reviewsNumber = Review::query()
            ->where('approved', '=', '0')
            ->count();

        $commentsNumber = Comment::query()
            ->where('approved', '=', '0')
            ->count();

        $ordersNumber = Order::query()
            ->where('status', '=', 'Pending')
            ->count();

    @endphp
    <div class="wrapper" style="float: none;">

        <header class="main-header">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">GAL</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">GALIO</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- reviews and comments notification -->
                        @if($commentsNumber)
                            <li title="New Comments">
                                <a href="{{ route('admin.blog.comment.index') }}">
                                    <i class="fa fa-comment"></i>
                                    <span class="label label-danger">{{ $commentsNumber }}</span>
                                </a>
                            </li>
                        @endif
                        @if($reviewsNumber)
                            <li title="New Reviews">
                                <a href="{{ route('admin.product.review.index') }}">
                                    <i class="fa fa-star"></i>
                                    <span class="label label-danger">{{ $reviewsNumber }}</span>
                                </a>
                            </li>
                        @endif
                        @if($ordersNumber)
                            <li title="New Orders">
                                <a href="">
                                    <i class="fa fa-check-square"></i>
                                    <span class="label label-danger">{{ $ordersNumber }}</span>
                                </a>
                            </li>
                        @endif

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu" style="background-color: #367fa9;">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="hidden-xs">
                                    <i class="fa fa-user"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header" style="height: fit-content;">
                                    <p>
                                        <b>Name:</b> {{ $user->name }}
                                        <br>
                                        <b>Email:</b> {{ $user->email }}
                                        <br>
                                        <b>Role:</b> {{ $user->admin->role }}
                                        <br>
                                        @if($user->admin->is_main_admin)
                                            <b>Main Admin</b>
                                        @endif
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('admin.profile') }}" class="btn btn-primary btn-flat">
                                            Profile
                                        </a>
                                    </div>
                                    <div class="pull-right">
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <input type="submit" class="btn btn-primary btn-flat" value="Log Out">
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li @class([
                        'active' => ($routeName[1] ?? '') == 'dashboard',
                    ])>
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="">
                            <i class="fa fa-check-square"></i>
                            <span>Orders</span>
                            @if($ordersNumber)
                                <span class="pull-right-container">
                                    <small class="label pull-right bg-blue">{{ $ordersNumber }}</small>
                                </span>
                            @endif
                        </a>
                    </li>

                    <li @class([
                        'treeview',
                        'active' => ($routeName[1] ?? '') == 'product',
                    ])>
                        <a href="">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Products</span>
                            <span class="pull-right-container">
                                @if($reviewsNumber)
                                    <i class="fa fa-circle pull-right text-blue"></i>
                                @endif
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'product.index',
                            ])>
                                <a href="{{ route('admin.product.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Products List
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'product.create',
                            ])>
                                <a href="{{ route('admin.product.create') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Create Product
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'product.review',
                            ])>
                                <a href="{{ route('admin.product.review.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Review
                                    @if($reviewsNumber)
                                        <span class="pull-right-container">
                                            <small class="label pull-right bg-blue">{{ $reviewsNumber }}</small>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'product.category',
                            ])>
                                <a href="{{ route('admin.product.category.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Categories
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'product.tag',
                            ])>
                                <a href="{{ route('admin.product.tag.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Tags
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li @class([
                        'treeview',
                        'active' => ($routeName[1] ?? '') == 'blog',
                    ])>
                        <a href="">
                            <i class="fa fa-newspaper-o"></i>
                            <span>Blogs</span>
                            <span class="pull-right-container">
                                @if($commentsNumber)
                                    <i class="fa fa-circle pull-right text-blue"></i>
                                @endif
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'blog.index',
                            ])>
                                <a href="{{ route('admin.blog.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Blogs List
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'blog.create',
                            ])>
                                <a href="{{ route('admin.blog.create') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Create Blog
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'blog.comment',
                            ])>
                                <a href="{{ route('admin.blog.comment.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Comments
                                    @if($commentsNumber)
                                        <span class="pull-right-container">
                                            <small class="label pull-right bg-blue">{{ $commentsNumber }}</small>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'blog.category',
                            ])>
                                <a href="{{ route('admin.blog.category.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Categories
                                </a>
                            </li>
                            <li @class([
                                'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'blog.tag',
                            ])>
                                <a href="{{ route('admin.blog.tag.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Tags
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li @class([
                            'treeview',
                            'active' => ($routeName[1] ?? '') == 'banner',
                        ])>
                        <a href="">
                            <i class="fa fa-sticky-note"></i>
                            <span>Banners</span>
                            <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                        </a>
                        <ul class="treeview-menu">
                            <li @class([
                                    'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'banner.index',
                                ])>
                                <a href="{{ route('admin.banner.index') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Banners List
                                </a>
                            </li>
                            <li @class([
                                    'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'banner.create',
                                ])>
                                <a href="{{ route('admin.banner.create') }}">
                                    <i class="fa fa-circle-o"></i>
                                    Create Banner
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li @class([
                        'active' => ($routeName[1] ?? '') == 'advertisement',
                    ])>
                        <a href="{{ route('admin.advertisement.index') }}">
                            <i class="fa fa-bullhorn"></i>
                            <span>Advertisements</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-envelope"></i>
                            <span>Subscribers</span>
                        </a>
                    </li>
                    <li @class([
                        'active' => ($routeName[1] ?? '') == 'coupon',
                    ])>
                        <a href="{{ route('admin.coupon.index') }}">
                            <i class="fa fa-dollar"></i>
                            <span>Coupon</span>
                        </a>
                    </li>
                    <li>
                        <a href="">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                     @if($user->admin->is_main_admin)
                        <li @class([
                            'treeview',
                            'active' => ($routeName[1] ?? '') == 'admin',
                        ])>
                            <a href="">
                                <i class="fa fa-cogs"></i>
                                <span>Admins</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li @class([
                                    'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'admin.index',
                                ])>
                                    <a href="{{ route('admin.admin.index') }}">
                                        <i class="fa fa-circle-o"></i>
                                        Admin List
                                    </a>
                                </li>
                                <li @class([
                                    'active' => implode('.', [$routeName[1] ?? '', $routeName[2] ?? '']) == 'admin.create',
                                ])>
                                    <a href="{{ route('admin.admin.create') }}">
                                        <i class="fa fa-circle-o"></i>
                                        Create Admin
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('content-header')
                </h1>
            </section>

            <!-- content -->
            <section class="content">


                @yield('content')


            </section>
            <!-- /.content -->

        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->






    <!-- jQuery 3 -->
    <script src="{{ asset('template_admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('template_admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template_admin/dist/js/adminlte.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template_admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        })
    </script>


    @yield('script')


</body>
</html>
