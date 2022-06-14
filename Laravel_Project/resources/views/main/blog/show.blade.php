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
                                <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blogs</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- product details wrapper -->
    <div class="blog-main-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 order-2">
                    <div class="blog-sidebar-wrapper mt-md-34 mt-sm-30">
                        <form action="{{ route('blog.index') }}" method="get" id="filter_form">
                            <input type="submit" name="submit" id="filter_submit" value="Submit" style="display: none">
                            <input type="hidden" id="category" name="category">
                            <input type="hidden" id="startDate" name="startDate">
                            <input type="hidden" id="tag" name="tag">

                            <div class="blog-sidebar mb-30">
                                <div class="sidebar-serch-form">
                                    <!-- Search in title -->
                                    <input type="text" name="search_title" class="search-field" placeholder="search here">
                                    <button type="submit" name="submit" value="Submit" class="search-btn"><i class="fa fa-search"></i></button>
                                </div>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-20">categories</h4>
                                <ul class="blog-archive">
                                    @foreach($categories as $category)
                                        <li><a href="javascript:clickSubmit('filter_submit', 'category', {{ $category->id }})">{{ $category->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-20">Blog Archives</h4>
                                <ul class="blog-archive">
                                    <li><a href="javascript:clickSubmit('filter_submit', 'startDate',
                                      '{{ \Carbon\Carbon::now()->format('Y-m-d') }}')">Today</a></li>
                                    <li><a href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subWeek()->format('Y-m-d') }}')">Last 7 days</a></li>
                                    <li><a href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subMonth()->format('Y-m-d') }}')">Last 30 days</a></li>
                                    <li><a href="javascript:clickSubmit('filter_submit', 'startDate',
                                        '{{ \Carbon\Carbon::now()->subMonth(12)->format('Y-m-d') }}')">Last 12 Months</a></li>
                                </ul>
                            </div> <!-- single sidebar end -->
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-30">Tags</h4>
                                <ul class="blog-tags">
                                    @foreach($tags as $tag)
                                        <li><a href="javascript:clickSubmit('filter_submit', 'tag', {{ $tag->id }})">{{ $tag->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </form>
                        <!-- single sidebar end -->
                        @if($recentBlogs->isNotEmpty())
                            <div class="blog-sidebar mb-24">
                                <h4 class="title mb-30">recent post</h4>
                                @foreach($recentBlogs as $recentBlog)
                                    <div class="recent-post mb-20">
                                        <div class="recent-post-thumb">
                                            <a href="{{ route('blog.show', ['blog' => $recentBlog->id]) }}">
                                                @if($recentBlog->images->isNotEmpty())
                                                    <img src="{{ $recentBlog->imagePath($recentBlog->images->first()) }}"
                                                         style="min-height: 63px; min-width: 63px;max-height: 63px; max-width: 63px; object-fit: cover">
                                                @else
                                                    <img src="{{ asset('storage/images/no_image_available/1.jpg') }}"
                                                         style="min-height: 63px; min-width: 63px;max-height: 63px; max-width: 63px; object-fit: cover">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="recent-post-des">
                                            <span style="display: -webkit-box;
                                                            -webkit-line-clamp: 1;
                                                            -webkit-box-orient: vertical;
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;">
                                                <a href="{{ route('blog.show', ['blog' => $recentBlog->id]) }}">{{ $recentBlog->title }}</a>
                                            </span>
                                            <span class="post-date">{{ $recentBlog->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-9 order-1">
                    <div class="blog-wrapper-inner">
                        <div class="row blog-content-wrap">
                            <!-- start single blog item -->
                            <div class="col-lg-12">
                                <div class="blog-item mb-30">
                                    <div class="blog-thumb img-full fix">
                                        <div class="blog-gallery-slider slick-arrow-style_2">
                                            @foreach($blog->images as $image)
                                                <div class="blog-single-slide img_container">
                                                    <img class="img" src="{{ $blog->imagePath($image) }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="blog-content">
                                        <div class="blog-details">
                                            <h3 class="blog-heading">{{ $blog->title }}</h3>
                                            <div class="blog-meta">
                                                <a class="author"><i class="icon-people"></i> {{ $blog->author }}</a>
                                                <a class="post-time"><i class="icon-calendar"></i> {{ $blog->created_at->format('d M Y') }}</a>
                                            </div>
                                            <div style="text-align: justify">{!! $blog->context !!}</div>
                                        </div>
                                    </div>
                                    <div class="tag-line">
                                        <h4>tag:</h4>
                                        @foreach($blog->tags as $tag)
                                            <a href="javascript:clickSubmit('filter_submit', 'tag', {{ $tag->id }})">{{ $tag->title }}</a>,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="comment-section">
                                    @if($comments->isEmpty())
                                        <h3>There isn't any comment</h3>
                                        <br>
                                    @else
                                        <h3 style="text-transform: none !important;">{{ $blog->commentsNumber() }} Comment(s)</h3>
                                        <ul>
                                            @foreach($comments as $comment)
                                                <li>
                                                    <div class="author-avatar">
                                                        <img src="{{ asset('template_main/assets/img/blog/comment-icon.png') }}" alt="">
                                                    </div>
                                                    <div class="comment-body">
                                                        <h5 class="comment-author">{{ $comment->author_name }}</h5>
                                                        <div class="comment-post-date">
                                                            {{ $comment->created_at->format('d M Y') }}
                                                        </div>
                                                        <p style="text-align: justify">{{ $comment->context }}</p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <!-- comment area start -->
                                <!-- start blog comment box -->
                                <div class="blog-comment-wrapper mb-sm-6">
                                    <h3>leave a comment:</h3>
                                    <form action="{{ route('blog.comment.store', ['blog' => $blog->id]) }}" method="post" id="create_comment">
                                        @csrf
                                        <div class="comment-post-box">
                                            <div class="row">
                                                <div @class([
                                                    'col-12', 'mb-20',
                                                    'error' => $errors->has('context'),
                                                ])>
                                                    <label>Context <span class="text-danger">*</span></label>
                                                    <textarea name="context" required>{{ old('context') }}</textarea>
                                                    @error('context')
                                                        <span>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div @class([
                                                    'col-lg-6', 'col-md-6', 'col-sm-6', 'mb-sm-20',
                                                    'error' => $errors->has('name'),
                                                ])>
                                                    <label>Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="coment-field" value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <span>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div @class([
                                                    'col-lg-6', 'col-md-6', 'col-sm-6', 'mb-sm-20',
                                                    'error' => $errors->has('email'),
                                                ])>
                                                    <label>Email <span class="text-danger">*</span></label>
                                                    <input type="email" name="email" class="coment-field" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <span>{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12">
                                                    <div class="coment-btn mt-20">
                                                        <input type="submit" name="submit" class="sqr-btn" value="Submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- start blog comment box -->
                            </div>
                            <!-- end single blog item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')

    @if($errors->any())
        <script>
            document.getElementById("create_comment").scrollIntoView();
        </script>
    @endif


    <script>
        function clickSubmit(id, input=null, value=null)
        {
            if (input != null)
                document.getElementById(input).value = value;

            document.getElementById(id).click();
        }
    </script>

@endsection
