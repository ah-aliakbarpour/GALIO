@extends('admin.layouts.app')



@section('title', 'Products List')


@section('style')

    <!-- bootstrap slider -->
    <link rel="stylesheet" href="{{ asset('template_admin/plugins/bootstrap-slider/slider.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('template_admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">

@endsection


@section('content-header', 'Products List')


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

    <form action="{{ route('admin.product.index') }}" method="get">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">Filter</h1>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Category</label>
                                <select name="category" class="form-control select2" style="width: 100%;">
                                    <option value="0" selected>All</option>
                                    <option value="none"
                                            @if(request()->input('category') == 'none') selected @endif>None</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                @if($category->id == request()->input('category')) selected @endif>
                                            {{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tag</label>
                                <select name="tags[]" class="form-control select2" multiple
                                        data-placeholder="All" style="width: 100%;">
                                    <option value="none"
                                            @if(in_array('none', request()->input('tags') ?? [])) selected @endif>
                                        None</option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                                @if(in_array($tag->id, request()->input('tags') ?? [])) selected @endif>
                                            {{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Price($)</label>
                                <br>
                                @php
                                    $inputPriceRange = explode(',',
                                        request()->input('price') ?? "{$priceRange->min},{$priceRange->max}");
                                    $inputMinPrice = $inputPriceRange[0];
                                    $inputMaxPrice = $inputPriceRange[1];
                                @endphp
                                <input name="price" type="text" value="" class="slider form-control"
                                       data-slider-min="{{ $priceRange->min }}" data-slider-max="{{ $priceRange->max }}" data-slider-step="0.01"
                                       data-slider-value="[
                                            {{ $inputMinPrice }},
                                            {{ $inputMaxPrice }}
                                           ]"
                                       data-slider-orientation="horizontal" data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Discount(%)</label>
                                <br>
                                @php
                                    $inputDiscountRange = explode(',', request()->input('discount') ?? '0,100');
                                    $inputMinDiscount = $inputDiscountRange[0];
                                    $inputMaxDiscount = $inputDiscountRange[1];
                                @endphp
                                <input name="discount" type="text" value="" class="slider form-control"
                                       data-slider-min="0" data-slider-max="100"
                                       data-slider-step="1" data-slider-value="[{{ $inputMinDiscount }},{{ $inputMaxDiscount }}]" data-slider-orientation="horizontal"
                                       data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Stock</label>
                                <select name="stock" class="form-control select2" data-placeholder="Select ..." style="width: 100%;">
                                    <option value="0" selected>All</option>
                                    <option value="in_stock"
                                            @if('in_stock' == request()->input('stock')) selected @endif>
                                        In Stock</option>
                                    <option value="out_of_stock"
                                            @if('out_of_stock' == request()->input('stock')) selected @endif>
                                        Out Of Stock</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Date Range:</label>
                                <div class="input-group">
                                    <input name="date" type="text" class="btn btn-default" id="daterange-btn">
                                </div>
                            </div>
                            <div class="form-group col-md-2" style="padding-left: 30px">
                                <label>Rating</label>
                                <br>
                                @php
                                    $inputRatingRange = explode(',',
                                        request()->input('rating') ?? '0,5');
                                    $inputMinRating = $inputRatingRange[0];
                                    $inputMaxRating = $inputRatingRange[1];
                                @endphp
                                <input name="rating" type="text" value="" class="slider form-control"
                                       data-slider-min="0" data-slider-max="5" data-slider-step="0.1"
                                       data-slider-value="[{{ $inputMinRating }},{{ $inputMaxRating }}]"
                                       data-slider-orientation="horizontal" data-slider-selection="before"
                                       data-slider-tooltip="show" data-slider-id="blue">
                            </div>
                            <div class="form-group col-md-1 pull-right">
                                <br>
                                <input name="submit" type="submit" class="btn btn-block btn-primary"  value="Filter">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
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
                                           value="{{ request()->input('search_title') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_title" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search_Title" id="submit_search_title" style="display: none">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Search In Introduction</label>
                                <div class="input-group" style="margin-bottom: 15px;">
                                    <input type="text" name="search_introduction" class="form-control" placeholder="Search"
                                           value="{{ request()->input('search_introduction') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_introduction" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search_Introduction" id="submit_search_introduction" style="display: none">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>Search In Description</label>
                                <div class="input-group" style="margin-bottom: 15px;">
                                    <input type="text" name="search_description" class="form-control" placeholder="Search"
                                           value="{{ request()->input('search_description') }}">
                                    <div class="input-group-btn">
                                        <label for="submit_search_description" class="btn btn-primary"><i class="fa fa-search"></i></label>
                                        <input type="submit" name="submit" value="Search_Description" id="submit_search_description" style="display: none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
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
                                        <option value="stock,asc" @if(request()->input('sort') == 'stock,asc') selected @endif>Stock (Law - High)</option>
                                        <option value="stock,desc" @if(request()->input('sort') == 'stock,desc') selected @endif>Stock (High - Law)</option>
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
    </form>

    @if(request()->input('submit'))
        <div class="row">
            <div class="col-md-12">
                <center>
                    <a href="{{ route('admin.product.index') }}">
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
                    <h3 class="box-title">Products</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    @if($products->isEmpty())
                        <h3 style="text-align: center">There isn't any product!</h3>
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
                                    <form id="selections" action="{{ route('admin.product.selections') }}" method="post">
                                        @csrf
                                        <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </th>
                                <td colspan="7">
                                    <center>Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} Of {{ $products->total() }} Results</center>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Label</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Stock</th>
                                <th>Rating</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $index = $products->firstItem();
                            @endphp
                            @foreach($products as $product)
                                <tr>
                                    <th>{{ $index++ }}</th>
                                    <td><input form="selections" type="checkbox" name="products_id[{{ $product->id }}]" class="products"></td>
                                    <td>
                                        @if($product->images->isNotEmpty())
                                            <a href="{{ $product->imagePath($product->images->first()) }}" target="_blank">
                                                <img src="{{ $product->imagePath($product->images->first()) }}"
                                                     alt="Img" height="50px" style="border: 1px solid black">
                                            </a>
                                        @else
                                            <span class="fa" style="height: 50px"></span>
                                        @endif
                                    </td>
                                    <td title="{{ $product->title }}">{{ \Illuminate\Support\Str::limit($product->title, 75   , '...') }}</td>
                                    <td><span class="label label-primary">{{ $product->label() }}</span></td>
                                    <td>{{ number_format($product->price, 2) }}$</td>
                                    <td>
                                        @if($product->discount)
                                            {{ $product->discount }}%
                                        @endif
                                    </td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->rating)
                                            {{ $product->rating }} <span style="color: orange;" class="fa fa-star"></span>
                                            &nbsp;
                                            <span style="color: grey;">({{ $product->reviewsNumber() }})</span>
                                        @endif
                                    </td>
                                    <td style="padding-top: 5px;">
                                        <a href="{{ route('admin.product.show', ['product' => $product->id]) }}"
                                           class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                        <a href="{{ route('admin.product.edit', ['product' => $product->id]) }}"
                                           class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                        <form action="{{ route('admin.product.destroy', ['product' => $product->id]) }}"
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
                            {{ $products->links('admin.layouts.pagination') }}
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
            for (let checkbox of document.getElementsByClassName('products'))
                checkbox.checked = this.checked;
        }
    </script>

    <!-- Bootstrap slider -->
    <script src="{{ asset('template_admin/plugins/bootstrap-slider/bootstrap-slider.js') }}"></script>
    <script>
        $(function () {
            $('.slider').slider()
        })
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

                    @if(request()->input('date'))
                        @php
                            $dateRange = explode(' - ', request()->input('date'));
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
