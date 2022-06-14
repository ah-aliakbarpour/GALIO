@extends('main.layouts.app')



@section('title', 'Blog Details')


@section('style')

    <style>
        .img_container {
            /*border: 1px solid red !important;*/
            position: relative !important;
            height: 545px !important;
        }
        .img {
            position: absolute !important;
            margin: auto !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            max-height: 545px !important;
            width: auto !important;
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
                                <li class="breadcrumb-item active" aria-current="page">Blogs</li>
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

    <!-- blog main wrapper start -->
    <div class="blog-main-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-2 order-lg-1">
                    <div class="blog-sidebar-wrapper">

                        @if(request()->input('submit'))
                            <div class="sidebar-widget mb-30">
                                <a href="{{ route('blog.index') }}">
                                    <button class="btn">Reset Filters</button>
                                </a>
                            </div>
                        @endif

                        <form action="{{ route('blog.index') }}" method="get" id="filter_form">
                            <input type="submit" name="submit" id="filter_submit" value="Submit" style="display: none">
                            <input type="hidden" id="category" name="category" value="{{ request()->input('category') }}">
                            <input type="hidden" id="startDate" name="startDate" value="{{ request()->input('startDate') }}">
                            <input type="hidden" id="tag" name="tag" value="{{ request()->input('tag') }}">

                            <div class="blog-sidebar mb-30">
                                <div class="sidebar-serch-form">
                                    <!-- Search in title -->
                                    <input type="text" name="search_title" class="search-field" placeholder="search here" value="{{ request()->input('search_title') }}">
                                    <button type="submit" name="submit" value="Submit" class="search-btn"><i class="fa fa-search"></i></button>
                                </div>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-20">categories</h4>
                                <ul class="blog-archive">
                                    @foreach($categories as $category)
                                        <li><a @class(['active' => request()->input('category') == $category->id])
                                               href="javascript:clickSubmit('filter_submit', 'category', {{ $category->id }})">{{ $category->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-20">Blog Archives</h4>
                                <ul class="blog-archive">
                                    <li><a @class(['active' => request()->input('startDate') == \Carbon\Carbon::now()->format('Y-m-d')])
                                           href="javascript:clickSubmit('filter_submit', 'startDate',
                                      '{{ \Carbon\Carbon::now()->format('Y-m-d') }}')">Today</a></li>
                                    <li><a @class(['active' => request()->input('startDate') == \Carbon\Carbon::now()->subWeek()->format('Y-m-d')])
                                           href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subWeek()->format('Y-m-d') }}')">Last 7 days</a></li>
                                    <li><a @class(['active' => request()->input('startDate') == \Carbon\Carbon::now()->subMonth()->format('Y-m-d')])
                                           href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subMonth()->format('Y-m-d') }}')">Last 30 days</a></li>
                                    <li><a @class(['active' => request()->input('startDate') == \Carbon\Carbon::now()->subMonth(12)->format('Y-m-d')])
                                           href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subMonth(12)->format('Y-m-d') }}')">Last 12 Months</a></li>
                                </ul>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-30">Tags</h4>
                                <ul class="blog-tags">
                                    @foreach($tags as $tag)
                                        <li><a @class(['active' => request()->input('tag') == $tag->id])
                                                href="javascript:clickSubmit('filter_submit', 'tag', {{ $tag->id }})">{{ $tag->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    @if($blogs->isEmpty())
                        <center>
                            <h3>There isn't any blog!</h3>
                        </center>
                    @else
                        <div class="blog-wrapper-inner">
                            <div class="row">
                                @foreach($blogs as $blog)
                                    <div class="col-lg-12">
                                        <div class="blog-item mb-26">
                                            <div class="blog-thumb img-full fix">
                                                <a href="{{ route('blog.show', ['blog' => $blog->id]) }}">
                                                    <center>
                                                        @if($blog->images->isNotEmpty())
                                                            <img src="{{ $blog->imagePath($blog->images->first()) }}" style="width: auto;" class="img-pri">
                                                        @else
                                                            <img src="{{ asset('storage/images/no_image_available/1.jpg') }}" style="width: auto;" class="img-pri">
                                                        @endif
                                                    </center>
                                                </a>
                                            </div>
                                            <div class="blog-content">
                                                <h3><a href="{{ route('blog.show', ['blog' => $blog->id]) }}">{{ $blog->title }}</a></h3>
                                                <div class="blog-meta">
                                                    <span class="posted-author">by: {{ $blog->author }}</span>
                                                    <span class="post-date">{{ $blog->created_at->format('d M Y') }}</span>
                                                </div>
                                                <p style="text-align: justify">{{ \Illuminate\Support\Str::limit($blog->context, 350, '...') }}</p>
                                            </div>
                                            <a href="{{ route('blog.show', ['blog' => $blog->id]) }}">read more <i class="fa fa-long-arrow-right"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- start pagination area -->
                        <center style="margin-inline: 10px">
                            {{ $blogs->links('main.layouts.pagination') }}
                        </center>
                        <!-- end pagination area -->
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- blog main wrapper end -->

@endsection


@section('script')

    <script>
        function clickSubmit(id, input=null, value=null)
        {
            if (input != null)
                document.getElementById(input).value = value;

            document.getElementById(id).click();
        }
    </script>

@endsection
