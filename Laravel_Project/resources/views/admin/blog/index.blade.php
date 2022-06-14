@extends('admin.layouts.app')



@section('title', 'Blogs List')


@section('style')

    <!-- bootstrap slider -->
    <link rel="stylesheet" href="{{ asset('template_admin/plugins/bootstrap-slider/slider.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('template_admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">

@endsection


@section('content-header', 'Blogs List')


@section('content')

    <!-- Alert -->
    @if(Session()->exists('alert'))
        <div class="row">
            <div class="col-md-12">
                <p class="callout callout-{{ Session()->get('alert')['type'] }}">
                    {{ Session()->get('alert')['massage'] }}
                </p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.blog.index') }}" method="get">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">Filter</h1>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Category</label>
                                <select name="category" class="form-control select2" style="width: 100%;">
                                    <option value="0" selected>All</option>
                                    <option value="none"
                                            @if(app('request')->input('category') == 'none') selected @endif>None</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($category->id == app('request')->input('category')) selected @endif>
                                            {{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tag</label>
                                <select name="tags[]" class="form-control select2" multiple
                                        data-placeholder="All" style="width: 100%;">
                                    <option value="none"
                                            @if(in_array('none', app('request')->input('tags') ?? [])) selected @endif>
                                        None</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                                @if(in_array($tag->id, app('request')->input('tags') ?? [])) selected @endif>
                                            {{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Date Range:</label>
                                <div class="input-group">
                                    <input name="date" type="text" class="btn btn-default" id="daterange-btn">
                                </div>
                            </div>
                            <div class="form-group col-md-2 pull-right">
                                <br>
                                <input name="submit" type="submit" class="btn btn-block btn-primary"  value="Filter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">Sort</h1>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Sort By</label>
                                    <select name="sort" class="form-control select2" style="width: 100%;">
                                        <option selected value="created_at,desc">Date (New - Old)</option>
                                        <option value="created_at,asc" @if(app('request')->input('sort') == 'created_at,asc') selected @endif>Date (Old - New)</option>
                                        <option value="title,asc" @if(app('request')->input('sort') == 'title,asc') selected @endif>Title (A - Z)</option>
                                        <option value="title,desc" @if(app('request')->input('sort') == 'title,desc') selected @endif>Title (Z - A)</option>
                                        <option value="author,asc" @if(app('request')->input('sort') == 'author,asc') selected @endif>Author (A - Z)</option>
                                        <option value="author,desc" @if(app('request')->input('sort') == 'author,desc') selected @endif>Author (Z - A)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 pull-right">
                                <div class="form-group" style="text-align: left;">
                                    <br>
                                    <input type="submit" name="submit" class="btn btn-block btn-primary" value="Sort">
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">Search</h1>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Search In Title</label>
                                <div class="input-group" style="margin-bottom: 15px;">
                                    <input type="text" name="search_title" class="form-control" placeholder="Search"
                                           value="{{ app('request')->input('search_title') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_title" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search Title" id="submit_search_title"
                                               style="display: none">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Search In Author</label>
                                <div class="input-group" style="margin-bottom: 15px;">
                                    <input type="text" name="search_author" class="form-control" placeholder="Search"
                                           value="{{ app('request')->input('search_author') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_author" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search Author" id="submit_search_author"
                                               style="display: none">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label>Search In Context</label>
                                <div class="input-group" style="margin-bottom: 15px;">
                                    <input type="text" name="search_context" class="form-control" placeholder="Search"
                                           value="{{ app('request')->input('search_context') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_context" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search Context" id="submit_search_context"
                                               style="display: none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </form>

    @if(app('request')->input('submit'))
        <div class="row">
            <div class="col-md-12">
                <center>
                    <a href="{{ route('admin.blog.index') }}">
                        <button class="btn btn-primary">Reset Filters</button>
                    </a>
                </center>
            </div>
        </div>
        <br>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Blogs</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    @if($blogs->isEmpty())
                        <h3 style="text-align: center">There isn't any blog!</h3>
                    @else
                        <table class="table table-hover">
                            <thead>
                                @error('selections')
                                <br>
                                <tr class="row">
                                    <div class="col-md-12">
                                        <div class="callout callout-danger">
                                            <p>{{ $message }}</p>
                                        </div>
                                    </div>
                                </tr>
                                @enderror
                                <tr>
                                    <th></th>
                                    <th><input type="checkbox" id="check_all"></th>
                                    <th>
                                        <form id="selections" action="{{ route('admin.blog.selections') }}" method="post">
                                            @csrf
                                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                        </form>
                                    </th>
                                    <td colspan="4">
                                        <center>Showing {{ $blogs->firstItem() }}–{{ $blogs->lastItem() }} Of {{ $blogs->total() }} Results</center>
                                    </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $index = $blogs->firstItem();
                                @endphp
                                @foreach($blogs as $blog)
                                    <tr>
                                        <th>{{ $index++ }}</th>
                                        <td><input form="selections" type="checkbox" name="blogs_id[{{ $blog->id }}]"
                                                   class="blogs"></td>
                                        <td>
                                            @if($blog->images->isNotEmpty())
                                                <a href="{{ $blog->imagePath($blog->images->first()) }}" target="_blank">
                                                    <img src="{{ $blog->imagePath($blog->images->first()) }}"
                                                         alt="Img" height="50px" style="border: 1px solid black">
                                                </a>
                                            @else
                                                <span class="fa" style="height: 50px"></span>
                                            @endif
                                        </td>
                                        <td title="{{ $blog->title }}">{{ \Illuminate\Support\Str::limit($blog->title, 75, '...') }}</td>
                                        <td title="{{ $blog->author }}">{{ \Illuminate\Support\Str::limit($blog->author, 35, '...') }}</td>
                                        <td>{{ $blog->created_at->format('Y/m/d') }}</td>
                                        <td style="padding-top: 5px;">
                                            <a href="{{ route('admin.blog.show', ['blog' => $blog->id]) }}"
                                               class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                            <a href="{{ route('admin.blog.edit', ['blog' => $blog->id]) }}"
                                               class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                            <form action="{{ route('admin.blog.destroy', ['blog' => $blog->id]) }}"
                                                  method="post" style="display: inline-block;">
                                                @csrf
                                                @method('delete')
                                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm" style="margin-top: 3px;">
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <center style="margin-inline: 10px">
                            {{ $blogs->onEachSide(1)->links('admin.layouts.pagination') }}
                        </center>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>


@endsection


@section('script')

    <script>
        // Check all
        document.getElementById('check_all').onclick = function () {
            for (let checkbox of document.getElementsByClassName('blogs'))
                checkbox.checked = this.checked;
        }
    </script>

    <!-- date-range-picker -->
    <script src="{{ asset('template_admin/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('template_admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- date-range-picker -->
    <script>
        $(function () {
            $('#daterange-btn').daterangepicker(
                {
                    locale: {
                        format: 'YYYY/MM/DD',
                    },
                    minDate: moment({!! json_encode($created_atRange->min, JSON_HEX_TAG) !!}).format('YYYY/MM/DD'),
                    maxDate: moment({!! json_encode($created_atRange->max, JSON_HEX_TAG) !!}).format('YYYY/MM/DD'),
                    //timePicker: true,
                    //timePicker24Hour: true,
                    ranges: {
                        'Ever'        : [
                            moment({!! json_encode($created_atRange->min, JSON_HEX_TAG) !!}),
                            moment({!! json_encode($created_atRange->max, JSON_HEX_TAG) !!}),
                        ],
                        'Today'       : [moment(), moment()],
                        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    },

                    @if(app('request')->input('date'))
                        @php
                            $dateRange = explode(' - ', app('request')->input('date'));
                            $startDate = $dateRange[0];
                            $endDate = $dateRange[1];
                        @endphp
                    startDate: moment({!! json_encode($startDate, JSON_HEX_TAG) !!}, 'YYYY/MM/DD'),
                    endDate  : moment({!! json_encode($endDate, JSON_HEX_TAG) !!}, 'YYYY/MM/DD'),
                    @else
                    startDate: moment({!! json_encode($created_atRange->min, JSON_HEX_TAG) !!}).format('YYYY/MM/DD'),
                    endDate  : moment({!! json_encode($created_atRange->max, JSON_HEX_TAG) !!}).format('YYYY/MM/DD'),
                    @endif
                },
                /*
                function (start, end) {
                    $('#daterange-btn').html(start.format('YYYY/MM/DD') + ' - ' + end.format('YYYY/MM/DD'))
                }
                */
            )
        })
    </script>

@endsection
