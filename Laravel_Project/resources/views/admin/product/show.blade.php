@extends('admin.layouts.app')



@section('title', 'Product Details')


@section('content-header', 'Product Details')

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

    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Images</th>
                            <td>
                                @forelse($product->images as $image)
                                    <a href="{{ $product->imagePath($image) }}" target="_blank">
                                        <img src="{{ $product->imagePath($image) }}" alt="" height="150px"
                                             style="margin-left: 5px; margin-top: 5px; border: 2px solid black">
                                    </a>
                                @empty
                                    <p>There isn't any image.</p>
                                @endforelse
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Title</th>
                                <td>{{ $product->title }}</td>
                            </tr>
                            <tr>
                                <th>Stock</th>
                                <td>{{ $product->stock }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>
                                    ${{ number_format($product->price, 2) }}
                                    @if($product->discount)
                                        -
                                        ${{ number_format($product->price * $product->discount / 100, 2) }}(Discount)
                                        =
                                        ${{ number_format($product->price * (100 - $product->discount) / 100, 2) }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td>
                                    @if($product->discount)
                                        %{{ $product->discount }}
                                    @else
                                        &#9866;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Rating</th>
                                <td>
                                    @if($product->rating())
                                        {{ $product->rating() }} <span style="color: orange;" class="fa fa-star"></span>
                                        &nbsp;
                                        <span style="color: grey;">{{ $product->reviewsNumber() }} review(s)</span>
                                    @else
                                        <p>There isn't any review</p>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Label</th>
                                <td>
                                    @if($product->label())
                                        <span class="label label-primary">{{ $product->label() }}</span>
                                    @else
                                        &#9866;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>
                                    @if($product->category)
                                        <span class="label label-default">{{ $product->category->title }}</span>
                                    @else
                                        &#9866;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tags</th>
                                <td>
                                    @forelse($product->tags as $tag)
                                        <span class="label label-default">{{ $tag->title }}</span>
                                    @empty
                                        &#9866;
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $product->created_at->format('Y/m/d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $product->updated_at->format('Y/m/d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Information</th>
                                <td>
                                    <table class="table table-hover">
                                        <tbody>
                                        @if($product->information->isNotEmpty())
                                            <tr>
                                                <th>Key</th>
                                                <th>Value</th>
                                            </tr>
                                        @endif
                                        @forelse($product->information as $information)
                                            <tr>
                                                <td>{{ $information->key }}</td>
                                                <td>{{ $information->value }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td>&#9866;</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Introduction</th>
                                <td>{!! $product->introduction ?? '&#9866;' !!}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{!! $product->description ?? '&#9866;' !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="{{ route('admin.product.edit', ['product' => $product->id]) }}"
                           class="btn btn-success btn-sm">Edit</a>
                        <form action="{{ route('admin.product.destroy', ['product' => $product->id]) }}"
                              method="post" style="display: inline-block;">
                            @csrf
                            @method('delete')
                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Pending Reviews</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            @if($pendingReviews->isEmpty())
                <h3 style="text-align: center">There isn't any review!</h3>
            @else
                <table class="table table-hover">
                    <thead>
                    @error('selections_pending')
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
                        <th><input type="checkbox" id="check_all_pending"></th>
                        <th colspan="9">
                            <form id="selections_pending" action="{{ route('admin.product.review.selections') }}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="pending">
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                <input type="submit" name="submit" value="Approve" class="btn btn-success btn-sm">
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Context</th>
                        <th>Author Name</th>
                        <th>Date</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pendingReviews as $review)
                        <tr>
                            <th>{{ $indexPendingReviews++ }}</th>
                            <td><input form="selections_pending" type="checkbox" name="reviews_id[{{ $review->id }}]"
                                       class="pending_reviews"></td>
                            <td>{{ \Illuminate\Support\Str::limit($review->context, 30, ' ...') }}</td>
                            <td>{{ $review->author_name }}</td>
                            <td>{{ $review->created_at->format('Y/m/d') }}</td>
                            <td><span style="color: orange;" class="fa fa-star"></span> {{ $review->rating }}</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.product.review.show', ['review' => $review->id]) }}" target="_blank"
                                   class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                <form action="{{ route('admin.product.review.approve', ['review' => $review->id]) }}"
                                      method="post" style="display: inline-block;">
                                    @csrf
                                    <input type="submit" name="submit" value=Approve class="btn btn-success btn-sm" style="margin-top: 3px;">
                                </form>
                                <form action="{{ route('admin.product.review.destroy', ['review' => $review->id]) }}"
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
                    {{ $pendingReviews
                        ->appends(['approvedReviews' => $approvedReviews->currentPage()])
                        ->onEachSide(1)
                        ->links('admin.layouts.pagination') }}
                </center>
            @endif
        </div>
    </div>

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Approved Reviews</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            @if($approvedReviews->isEmpty())
                <h3 style="text-align: center">There isn't any review!</h3>
            @else
                <table class="table table-hover">
                    <thead>
                    @error('selections_approved')
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
                        <th><input type="checkbox" id="check_all_approved"></th>
                        <th colspan="9">
                            <form id="selections_approved" action="{{ route('admin.product.review.selections') }}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="approved">
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Context</th>
                        <th>Author Name</th>
                        <th>Date</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($approvedReviews as $review)
                        <tr>
                            <th>{{ $indexApprovedReviews++ }}</th>
                            <td><input form="selections_approved" type="checkbox" name="reviews_id[{{ $review->id }}]"
                                       class="approved_reviews"></td>
                            <td>{{ \Illuminate\Support\Str::limit($review->context, 30, ' ...') }}</td>
                            <td>{{ $review->author_name }}</td>
                            <td>{{ $review->created_at->format('Y/m/d') }}</td>
                            <td><span style="color: orange;" class="fa fa-star"></span> {{ $review->rating }}</td>
                            <td><span class="label label-success">Approved</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.product.review.show', ['review' => $review->id]) }}" target="_blank"
                                   class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                <form action="{{ route('admin.product.review.destroy', ['review' => $review->id]) }}"
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
                    {{ $approvedReviews
                        ->appends(['pendingReviews' => $pendingReviews->currentPage()])
                        ->onEachSide(1)
                        ->links('admin.layouts.pagination') }}
                </center>
            @endif
        </div>
    </div>


@endsection


@section('script')

    <script>
        // Check all | Pending
        // Why did we add the if statement? If we don't do this check all approved doesn't
        // work when there isn't any pending review!!!
        @if($pendingReviews->isNotEmpty())
        document.getElementById('check_all_pending').onclick = function () {
            for (let checkbox of document.getElementsByClassName('pending_reviews'))
                checkbox.checked = this.checked;
        }
        @endif

        // Check all | Approved
        document.getElementById('check_all_approved').onclick = function () {
            for (let checkbox of document.getElementsByClassName('approved_reviews'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
