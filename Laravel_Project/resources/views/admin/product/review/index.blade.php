@extends('admin.layouts.app')



@section('title', 'Reviews')


@section('content-header', 'Reviews')


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
                        <th>Product Title</th>
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
                            <td>
                                <a href="{{ route('admin.product.show', ['product' => $review->product->id]) }}">
                                    {{ \Illuminate\Support\Str::limit($review->product->title, 50, '...') }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($review->context, 30, ' ...') }}</td>
                            <td>{{ $review->author_name }}</td>
                            <td>{{ $review->created_at->format('Y/m/d') }}</td>
                            <td><span style="color: orange;" class="fa fa-star"></span> {{ $review->rating }}</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.product.review.show', ['review' => $review->id]) }}"
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
                        <th>Product Title</th>
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
                            <td>
                                <a href="{{ route('admin.product.show', ['product' => $review->product->id]) }}">
                                    {{ \Illuminate\Support\Str::limit($review->product->title, 50, '...') }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($review->context, 30, ' ...') }}</td>
                            <td>{{ $review->author_name }}</td>
                            <td>{{ $review->created_at->format('Y/m/d') }}</td>
                            <td><span style="color: orange;" class="fa fa-star"></span> {{ $review->rating }}</td>
                            <td><span class="label label-success">Approved</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.product.review.show', ['review' => $review->id]) }}"
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
